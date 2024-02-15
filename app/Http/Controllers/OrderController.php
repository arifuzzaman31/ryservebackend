<?php

namespace App\Http\Controllers;

use App\Exports\OrderExport;
use App\Http\AllStatic;
use App\Models\Order;
use App\Models\Delivery;
use App\Models\OrderDetails;
use App\Models\UserBillingInfo;
use App\Models\UserShippingInfo;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Xenon\MultiCourier\Provider\ECourier;
use Xenon\MultiCourier\Courier;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pickup = DB::table('pickuphubs')->where('status',1)->get();
        return view('pages.order.order',['pickups' => $pickup]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getOrder(Request $request)
    {
        $noPagination = $request->get('no_paginate');
        $taken = $request->get('take_some');
        $keyword   = $request->get('keyword');
        $byposition   = $request->get('byposition');
        $payment_status   = $request->get('payment_status');
        $status   = $request->get('status');
        $from   = $request->get('from');
        $to   = $request->get('to');
        $to = date('Y-m-d', strtotime("+1 day", strtotime($to)));
        if($request->excel == 'yes'){
            return Excel::download(new OrderExport($request->keyword,$request->byposition,$request->status),'order_list.xlsx');
        }
        if($request->invoicexcel == 'yes'){
            return Excel::download(new OrderExport($request->keyword,$request->byposition,$request->status,'invoice'),'invoice_report.xlsx');
        }
        $dataQty = $request->get('per_page') ? $request->get('per_page') : 12;

        $order = Order::with(['user','delivery','user_shipping_info'])->withCount([
            'order_details as buying_sum' => function($query) {
                $query->select(\DB::raw('SUM(total_buying_price)'));
            }
        ])->orderBy('id','desc');

        if($keyword != ''){
            $order = $order->where('id','like','%'.$keyword.'%')
            ->orWhereHas('user_shipping_info', function ($q) use ($keyword) {
                $q->where('last_name','like','%'.$keyword.'%')
                    ->orWhere('phone','like','%'.$keyword.'%')
                    ->orWhere('email','like','%'.$keyword.'%');
            });
        }

        if($payment_status != ''){
            $order = $order->where('payment_status',$payment_status);
        }

        if($byposition != ''){
            $order = $order->where('order_position',$byposition);
        }

        if($status != ''){
            $order = $order->where('status',$status);
        }

        if($from != '' && $to != ''){
            $order = $order->whereBetween('order_date',[$from,$to]);
        }
        if($noPagination != ''){
            if($taken != ''){
                $order = $order->take($taken);
            }
            $order = $order->get();
        } else {
            $order = $order->paginate($dataQty);
        }
        return response()->json($order);
    }


    public function getUserOrder(Request $request,$id)
    {
        $noPagination = $request->get('no_paginate');
        $dataQty = $request->get('per_page') ? $request->get('per_page') : 12;
        $order = Order::where('user_id',$id)->orderBy('id','desc');
        if($noPagination != ''){
            $order = $order->get();
        } else {
            $order = $order->paginate($dataQty);
        }
        return response()->json($order);
    }

    public function orderDetails(Request $request,$id)
    {

        try{
            if($request->from == 'pdf'){
               $orderdata = Order::find($id);
            //    return view('invoice',['order_info' => $orderdata]);
                $pdf = \PDF::loadView('invoice',['order_info' => $orderdata]);
                return $pdf->download('invoice-'.time().'-'.$orderdata->id.'.pdf');
            }
            $order = Order::with('user','delivery','user_shipping_info','user_billing_info')->find($id);
            $details = OrderDetails::with(['product','colour','size','fabric'])->where('order_id',$id)->get();
            $pickup = DB::table('pickuphubs')->where('status',1)->get();
            return view('pages.order.order_details',['details' => $details,'order' => $order,'pickups' => $pickup]);
        } catch (\Throwable $th) {
                return $th->getMessage();
        }
    }

    public function orderCancel(Request $request)
    {
        $order = Order::find($request->order_id);
        $order->status = $request->order_modify;
        $order->update();
        return response()->json(['status' => 'success', 'message' => 'Order Has been Modified!']);
    }

    public function orderPaymentStatus(Request $request)
    {
        $order = Order::find($request->order_id);
        $order->payment_status = $request->payment_status;
        $order->payment_date = date("Y-m-d");
        $order->update();
        return response()->json(['status' => 'success', 'message' => 'Order Payment Changed!']);
    }

    public function orderShipment($id)
    {
        try {
            return Order::select('id','order_id','order_position','tracking_id')->find($id);
        } catch (\Throwable $th) {
            return $this->errorMessage();
            //throw $th;
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateOrderStatus(Request $request)
    {
        try {
            // return $request->all();
            DB::beginTransaction();
            $order = Order::find($request->order_id);
            $order->order_position = $request->order_position;
            $order->update();

            $delivery = Delivery::where('order_id',$request->order_id)->first();
            if(!empty($delivery)){
                if($request->order_position == AllStatic::$pending){
                    $delivery->process_date = NULL;
                    $delivery->process_state = NULL;
                    $delivery->process_value = NULL;
                    $delivery->on_delivery_date = NULL;
                    $delivery->on_delivery_state = NULL;
                    $delivery->on_delivery_value = NULL;
                    $delivery->delivery_date = NULL;
                    $delivery->delivery_state = NULL;
                    $delivery->delivery_value = NULL;
                }
                if($request->order_position == AllStatic::$processing){
                    $delivery->process_date = $request->date ? $request->date : date('Y-m-d');
                    $delivery->process_state = AllStatic::$processing;
                    $delivery->process_value = deliveryPosition(AllStatic::$processing);
                    $delivery->on_delivery_date = NULL;
                    $delivery->on_delivery_state = NULL;
                    $delivery->on_delivery_value = NULL;
                    $delivery->delivery_date = NULL;
                    $delivery->delivery_state = NULL;
                    $delivery->delivery_value = NULL;
                    if(empty($delivery->tracking_id)){
                        if($order->delivery_platform == 'E-Courier'){
                               $this->sendEcorier($order,$request->hub_name);
                        }
                    }
                }

                if($request->order_position == AllStatic::$on_delivery){
                    $delivery->on_delivery_date = $request->date ? $request->date : date('Y-m-d');
                    $delivery->on_delivery_state = AllStatic::$on_delivery;
                    $delivery->on_delivery_value = deliveryPosition(AllStatic::$on_delivery);
                    $delivery->delivery_date = NULL;
                    $delivery->delivery_state = NULL;
                    $delivery->delivery_value = NULL;
                }

                if($request->order_position == AllStatic::$delivered){
                    $delivery->delivery_date = $request->date ? $request->date : date('Y-m-d');
                    $delivery->delivery_state = AllStatic::$delivered;
                    $delivery->delivery_value = deliveryPosition(AllStatic::$delivered);
                }
                $delivery->update();
            }
            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Order Status Updated!']);

        } catch (\Throwable $th) {
            DB::rollBack();
            // return $th;
            return response()->json(['status' => 'error', 'message' => 'Something went wrong!']);
            //throw $th;
        }

        return response()->json($request->id);
    }

    public function sendEcorier($order,$hubInfo)
    {
        try {
            $courier = Courier::getInstance();
            $courier->setProvider(ECourier::class, config('app.payment_mode')); /* local/production */
            $courier->setConfig([
                'API-KEY' => env('ECOURIER_API_KEY'),
                'API-SECRET' => env('ECOURIER_API_SECRET'),
                'USER-ID' => env('ECOURIER_USER_ID')
            ]);
            $ecorier = json_decode($order->courier_details);
            $courierData = [
                'recipient_name' => $ecorier->recipient_name,
                'recipient_mobile' => $ecorier->recipient_mobile,
                'recipient_city' => $ecorier->recipient_city,
                'recipient_area' => $ecorier->recipient_area,
                'recipient_thana' => $ecorier->recipient_thana,
                'recipient_address' => $ecorier->recipient_address,
                'package_code' => $ecorier->package_code,
                'product_price' => ($order->total_price + $order->shipping_amount + $order->vat_amount),
                'payment_method' => 'COD',
                'recipient_landmark' => 'DBBL ATM',
                'parcel_type' => 'BOX',
                'requested_delivery_time' => $order->requested_delivery_date,
                'delivery_hour' => 'any',
                'recipient_zip' => $ecorier->recipient_zip,
                'pick_hub' => $hubInfo['hub_code'] ?? '18490',
                'product_id' => $order->order_id,
                'pick_address' => $hubInfo['hub_address']?? "Banani",
                'comments' => $order->user_note ?? 'Please handle carefully',
                'number_of_item' => $order->total_item,
                'actual_product_price' => $order->total_price,
                'pgwid' => '8888',
                'pgwtxn_id' => $order->order_id,
                'is_fragile' => $order->total_fragile_amount > 0 ? 1 : 0,
                'sending_type' => 1,
                'is_ipay' => 0
            ];

            $courier->setParams($courierData);
            $resp = $courier->placeOrder();
            $result = json_decode(json_encode($resp),true);
            $datas = json_decode($result['response']);
            if ($datas !== null && isset($datas->ID)) {

                    if($result['statusCode'] == 200){
                        DB::table('orders')->where('id', $order->id)->update([
                            'tracking_id' => $datas->ID,
                            'updated_at'    => date("Y-m-d H:i:s")
                        ]);
                        DB::table('deliveries')->where('order_id', $order->id)->update([
                            'tracking_id' => $datas->ID
                        ]);
                    }
            }
            return false;
        } catch (\Throwable $th) {
            return false;
            // return $th;
        }

    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order,$id)
    {
        response()->json(Order::find(2));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        response()->json(Order::find(2));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        response()->json(Order::find(2));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order,$id)
    {
        try {
            DB::beginTransaction();
            $order = $order->find($id);
            if(empty($order)){
                return response()->json(['status' => 'error', 'message' => 'Order Not Found!']);
            }
            OrderDetails::where('order_id',$id)->delete();
            Delivery::where('order_id',$id)->delete();
            UserBillingInfo::where('order_id',$id)->delete();
            UserShippingInfo::where('order_id',$id)->delete();
            $order->delete();
            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Order Deleted Successfully!']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => 'Something went wrong!']);
        }
    }
}
