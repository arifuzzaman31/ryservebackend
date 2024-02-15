<?php

namespace App\Http\Controllers\Payment;

use App\Http\AllStatic;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Product;
use DB;
class SslController extends Controller
{
    public function sslCommerz(Request $request,$order_id)
    {
        $order = Order::with('user','user_billing_info')->find($order_id);
        $order_details = OrderDetails::with('product:id,product_name,mrp_price')->where('order_id', $order_id)->get();
        
        $post_data = array();
        $post_data['store_id'] = "limme601b86f8e6dd4";
        $post_data['store_passwd'] = "limme601b86f8e6dd4@ssl";
        $post_data['total_amount'] = ($order->total_price + $order->shipping_amount + $order->vat_amount) - $order->coupon_discount;
        $post_data['currency'] = "BDT";
        $post_data['tran_id'] = $order_id;
        $post_data['success_url'] = route('ssl.success');
        $post_data['fail_url']     = config('app.front_url');
        $post_data['cancel_url']   = config('app.front_url');
    
        # CUSTOMER INFORMATION
        $post_data['cus_name'] = $order->user_billing_info->first_name;
        $post_data['cus_email'] = $order->user_billing_info->email;
        $post_data['cus_add1'] = $order->user_billing_info->city;
        $post_data['cus_add2'] = $order->user_billing_info->city;
        $post_data['cus_city'] = $order->user_billing_info->city;
        $post_data['cus_state'] = $order->user_billing_info->city;
        $post_data['cus_postcode'] = $order->user_billing_info->post_code;
        $post_data['cus_country'] = "Bangladesh";
        $post_data['cus_phone'] = $order->user_billing_info->phone;
        $post_data['cus_fax'] = "";
    
        # SHIPMENT INFORMATION
        $post_data['ship_name'] = "Aranya";
        $post_data['shipping_method'] = "NO";
        $post_data['ship_add1 '] = "Dhaka";
        $post_data['ship_add2'] = "Dhaka";
        $post_data['ship_city'] = "Dhaka";
        $post_data['ship_state'] = "Dhaka";
        $post_data['ship_postcode'] = "1000";
        $post_data['ship_country'] = "Bangladesh";
    
        # OPTIONAL PARAMETERS
        $post_data['value_a'] = Auth::id();
        $post_data['value_b '] = "ref002";
        $post_data['value_c'] = "ref003";
        $post_data['value_d'] = "ref004";
    
        # EMI STATUS
        $post_data['emi_option'] = "1";
    
        # CART PARAMETERS
        $product_name = [];

        foreach ($order_details as $value) {
            $arr = [];
            $arr['product']  =  $value->product->product_name;
            $arr['amount']   =  $value->total_selling_price;

            array_push($product_name, $arr);
        }
        $product_name = json_encode($product_name);

        $post_data['cart'] = json_encode($product_name);
        $post_data['product_name'] = json_encode($product_name);
        $post_data['product_category'] = "online shop";
        $post_data['product_profile'] = "general";
        $post_data['product_amount'] = $order->total_price;
        $post_data['vat'] = $order->vat_rate;
        $post_data['discount_amount'] = $order->vat_amount;
        $post_data['convenience_fee'] = $order->shipping_amount;
    
        //$post_data['allowed_bin'] = "3,4";
        //$post_data['allowed_bin'] = "470661";
        //$post_data['allowed_bin'] = "470661,376947";
    
        // dd($post_data);
        # REQUEST SEND TO SSLCOMMERZ
        $direct_api_url = "https://sandbox.sslcommerz.com/gwprocess/v4/api.php";
       
        $handle = curl_init();
        curl_setopt($handle, CURLOPT_URL, $direct_api_url );
        curl_setopt($handle, CURLOPT_TIMEOUT, 30);
        curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($handle, CURLOPT_POST, 1 );
        curl_setopt($handle, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, 1); # KEEP IT FALSE IF YOU RUN FROM LOCAL PC


        $content = curl_exec($handle );

        $code = curl_getinfo($handle, CURLINFO_HTTP_CODE);

        if($code == 200 && !( curl_errno($handle))) {
            curl_close( $handle);
            $sslcommerzResponse = $content;
        } else {
            curl_close( $handle);
            echo "FAILED TO CONNECT WITH SSLCOMMERZ API";
            exit;
        }
        
        // dd($content);
        # PARSE THE JSON RESPONSE
        $sslcz = json_decode($sslcommerzResponse, true );
        // var_dump($sslcz); exit;
    
        if (isset($sslcz['GatewayPageURL']) && $sslcz['GatewayPageURL'] != "") {
            // return redirect()->to($sslcz['GatewayPageURL']);
             return json_encode(['status' => 'success', 'data' => $sslcz['GatewayPageURL'], 'logo' => $sslcz['storeLogo']]);
        } else {
            // return $sslcz;
            return json_encode(['status' => 'fail', 'data' => null, 'message' => "something went wrong!"]);
        }

    }

    public function sslCommerzSuccess(Request $request)
    {
        if ($request->status == "VALID") {
            try
            {
                DB::beginTransaction();
                $order = Order::where('id',$request->tran_id)->first();

                $order->payment_status = AllStatic::$paid;
                $order->payment_method_name = 'sslCommerz';
                $order->card_type = $request->card_type;
                $order->validation_id = $request->val_id;
                $order->transaction_id = $request->bank_tran_id;
                $order->payment_date = $request->tran_date;
                $order->payment_info = json_encode($request->all());
                $order->update();


                if ($order->payment_status == 1) {

                    return redirect('http://localhost:3000');
                }

                DB::commit();

                return redirect('http://localhost:3000');

            } catch (\Throwable $th) {
                // return $th;
                DB::rollBack();
                return response()->json(['status' => 'error', 'message' => 'something went wrong'], 400);
            }

        }

    }

    public function sslCommerzFailed(Request $request)
    {
        $status  = 'error';
        $message = '';

        // if(!Auth::check()){
               
        //     Auth::loginUsingId($request->value_a);
        // }
        if ($request->status == 'FAILED') {

            $message = 'Payment failed for order #'.$request->tran_id.' due to ' . $request->error. '.';

        } 
        else {

            $message = 'Something went wrong and your payment failed  for order #'.$request->tran_id.'.';
        }

        return response()->json([

            'flug'   => 1,
            'status' => $status,
            'message' => $message,
        ]);

    }

    public function sslCommerzCancel(Request $request)
    {
        return "Cancelled";
        // return $request->all();
        if(!Auth::check()){
               
            Auth::loginUsingId($request->value_a);
        }
        return response()->json([
            'flug'   => 1,
            'status' => 'error',
            'message' => 'The payment has been canceled for order #'.$request->tran_id.'.',
        ]);

    }
    
}