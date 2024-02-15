<?php

namespace App\Http\Controllers;

use App\Http\AllStatic;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderDetails;

class CustomerController extends Controller
{
    public function index()
    {
        return view('pages.customer.customer');
    }

    public function getCustomerOrder($id)
    {
        $order = Order::with(['delivery','user:id,name'])->where('user_id',$id)->orderBy('id','desc')
        ->paginate(10);
        
        return view('pages.customer.order',['orders' => $order]);

        // $query->withCount([
        //     'activity AS paid_sum' => function ($query) {
        //                 $query->select(DB::raw("SUM(amount_total) as paidsum"))->where('status', 'paid');
        //             }
        //         ]);
    }

    public function getCustomerOrderDetail($id)
    {
        $order = Order::where('id',$id)->with(['delivery','user_shipping_info','user_billing_info'])->orderBy('id','desc')->first();
        $details = OrderDetails::with(['product','size','fabric','order'])->where(['order_id'=> $id])->get();
        
        return view('pages.customer.order_details',['details' => $details,'orders' => $order]);
    }

    public function getCustomer(Request $request)
    {
        $noPagination = $request->get('no_paginate');
        $dataQty = $request->get('per_page') ? $request->get('per_page') : 10;

        $user = User::with(['order_details' => function($query) {
            return $query->groupBy('user_id')->selectRaw('sum(total_selling_price) as successfull_paid,user_id')
                ->where('is_refunded',AllStatic::$inactive)->get();
        }])->whereHas('order_details')->orderBy('id','desc');

        if($request->keyword != ''){
            $user = $user->where('name','like','%'.$request->keyword.'%');
            $user = $user->orWhere('email','like','%'.$request->keyword.'%');
            $user = $user->orWhere('phone','like','%'.$request->keyword.'%');
        }
        if($noPagination != ''){
            $user = $user->get();
        } else {
            $user = $user->paginate($dataQty);
        }
        return response()->json($user);

    }
}
