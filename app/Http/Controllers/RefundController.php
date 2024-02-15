<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\OrderDetails;
use App\Http\AllStatic;

class RefundController extends Controller
{
    public function index()
    {
        return view('pages.refund.refund');
    }

    public function configure()
    {
        $configure = DB::table('refund_configure')->first();
        return view('pages.refund.configure',['configure' => json_encode($configure)]);
    }

    public function update(Request $request)
    {
        try {
            // return response()->json(['status' => 'success', 'message' => 'Refund Updated Successful!']);

            DB::table('refund_configure')
                ->where('id',$request->id)
                ->update([
                    'refund_within_days' => $request->refund_within_days,
                    'status' => $request->status
                ]);

            return response()->json(['status' => 'success', 'message' => 'Refund Updated Successful!']);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'message' =>  $th->getMessage()]);
        }
    }

    public function refundItemDetail(Request $request)
    {
        $from   = $request->get('from');
        $keyword   = $request->get('keyword');
        $details = OrderDetails::with(['user','product:id,product_name','order:id,payment_status','size','fabric']);

        if($from != '' && $from == 'request-refund'){
            $details = $details->where('is_refunded',AllStatic::$inactive)->where('is_claim_refund',AllStatic::$active);
        }

        if($from != '' && $from == 'approve-refund'){
            $details = $details->where('is_refunded',AllStatic::$active)->where('is_claim_refund',AllStatic::$active);
        }

        if($from != '' && $from == 'reject-refund'){
            $details = $details->where('is_refunded',AllStatic::$failed)->where('is_claim_refund',AllStatic::$active);
        }

        if($keyword != ''){
            $details = $details->where('order_id','like','%'.$keyword.'%');
        }

        $details = $details->paginate(10);

        return response()->json($details);
    }

    public function orderItemRefund(Request $request)
    {
        try {
            $order_detail = OrderDetails::find($request->id);
            if($order_detail->is_refunded == AllStatic::$paid){
                return response()->json(['status' => 'error','message' => 'Already Refunded']);
            }
            if($request->refund_status == 2) {
                $order_detail->is_refunded = AllStatic::$failed;
                $order_detail->refund_reject_reason = $request->reason;
                $order_detail->refund_date = date("Y-m-d");
                $order_detail->update();
                return response()->json(['status' => 'success','message' => 'Refund Request Rejected!']);
            }

            // $order_detail->is_refunded = AllStatic::$active;
            // $order_detail->refund_date = date("Y-m-d");
            // $order_detail->update();
            // return response()->json(['status' => 'success','message' => 'Refund Approved!']);

            $bank_tran_id=urlencode($order_detail->order->transaction_id);
            $refund_amount=urlencode(($order_detail->total_selling_price + $order_detail->vat_amount));
            $refund_remarks=urlencode('Out of Stock');
            $store_id=urlencode(config('app.storeid'));
            $store_passwd=urlencode(config('app.storepassw'));

            $requested_url = ("https://sandbox.sslcommerz.com/validator/api/merchantTransIDvalidationAPI.php?refund_amount=$refund_amount&refund_remarks=$refund_remarks&bank_tran_id=$bank_tran_id&store_id=$store_id&store_passwd=$store_passwd&v=1&format=json");

            $handle = curl_init();
            curl_setopt($handle, CURLOPT_URL, $requested_url);
            curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, false); # IF YOU RUN FROM LOCAL PC
            curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false); # IF YOU RUN FROM LOCAL PC

            $result = curl_exec($handle);

            $code = curl_getinfo($handle, CURLINFO_HTTP_CODE);

            if($code == 200 && !( curl_errno($handle)))
            {

                # TO CONVERT AS ARRAY
                # $result = json_decode($result, true);
                # $status = $result['status'];

                # TO CONVERT AS OBJECT
                $result = json_decode($result);

                # TRANSACTION INFO
                $status = $result->status;
                $bank_tran_id = $result->bank_tran_id;
                //$trans_id = $result->trans_id;
                //$refund_ref_id = $result->refund_ref_id ?? $result->refund_ref_id;
                $errorReason = $result->errorReason;

                # API AUTHENTICATION
                $APIConnect = $result->APIConnect;

                DB::table('payments')->where('order_id', $order_detail->order_id)->update([
                     'is_refunded' => AllStatic::$active,
                     'refund_date' => date("Y-m-d"),
                     'refund_info' => json_encode($result),
                     'updated_at'    => date("Y-m-d H:i:s")
                 ]);

                $order_detail->is_refunded = AllStatic::$active;
                $order_detail->refund_date = date("Y-m-d");
                $order_detail->refund_info = json_encode($result);
                $order_detail->update();
               return response()->json(['status' => $status,'message' => 'Refund Successfully Done']);
            } else {
                return $this->errorMessage();
            }


        } catch (\Throwable $th) {
            // return $th;
            return $this->errorMessage();
        }
    }
}
