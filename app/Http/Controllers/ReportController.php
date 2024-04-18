<?php

namespace App\Http\Controllers;

use App\Exports\Report\CampaignReportExport;
use App\Exports\Report\LifetimeReportExport;
use App\Exports\Report\SalesReportExport;
use App\Exports\Report\StockReportExport;
use App\Exports\Report\RefundReportExport;
use App\Exports\Report\IndividualReportExport;
use App\Exports\Report\PaymentReportExport;
use App\Models\User;
use App\Models\Order;
use App\Http\AllStatic;
use App\Models\Inventory;
use App\Models\OrderDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function revenueReport(Request $request)
    {
        try {
            $from   = $request->get('date_from');
            $to   = $request->get('date_to');
            $token   = $request->get('token');
            $token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6IjY1ZmJmYjZkYzRiMWU1MTdkNDhlNjE0NCIsIm5hbWUiOiJXZWJhYmxlIERpZ2l0YWwiLCJlbWFpbCI6IndlYmFibGVAZGlnaXRhbC5jb20iLCJwaG9uZU51bWJlciI6IjAxNTg4MDc5Mzg1IiwicGxhdGZvcm0iOiJEQVNIQk9BUkRfVVNFUiIsImlhdCI6MTcxMzQxNzMxMCwiZXhwIjoxNzQ0OTUzMzEwfQ.2aw97araoiuFG4FY3ADKfLgSzPBIJC18Bdt-RzkBuVQ";
            // $to = date('Y-m-d', strtotime("+1 day", strtotime($to)));
            // return $token;
            if($request->excel == 'yes'){
                $curl = curl_init();
                curl_setopt_array($curl, array(
                CURLOPT_URL => "http://localhost:3000/api/backendapi/report",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer $token"
                ),
                ));

                $response = curl_exec($curl);
                $data = json_decode($response, true);
                curl_close($curl);
                return response()->json(['dt' => $data]);
                return \Excel::download(new StockReportExport($from,$to),'stock_report.xlsx');
            }

        } catch (\Throwable $th) {
            // return $th;
            return $this->errorMessage();
        }
    }

    public function salesReport(Request $request)
    {
        try {
            $from   = $request->get('date_from');
            $to   = $request->get('date_to');
            $to = date('Y-m-d', strtotime("+1 day", strtotime($to)));
            $category   = $request->get('category');
            $subcategory   = $request->get('subcategory');
            $brand   = $request->get('brand');
            $keyword   = $request->get('keyword');
            if($request->excel == 'yes'){
                return \Excel::download(new SalesReportExport($from,$to),'sales_report.xlsx');
            }
            $dataQty = $request->get('per_page') ? $request->get('per_page') : 12;

            $data = Inventory::with('product:id,design_code,category_id,sub_category_id,fragile,fragile_charge,weight,lead_time',
                'product.category:id,category_name','product.subcategory:id,category_name',
                'product.product_brand:id,brand_name','product.product_fabric:id,fabric_name','colour:id,color_name',
                'product.product_size:id,size_name','product.product_designer:id,designer_name','product.product_embellishment:id,embellishment_name',
                'product.product_making:id,making_name','product.product_season:id,season_name','product.product_variety:id,variety_name',
                'product.product_fit:id,fit_name','product.product_artist:id,artist_name','product.product_consignment:id,consignment_name',
                'product.product_ingredient:id,ingredient_name')
                ->selectRaw('order_details.product_id, sum(quantity) as sales_quantity,sum(total_selling_price) as total_selling_amount,
                    sum(vat_amount) as total_vat_amount,ROUND(sum(total_buying_price),2) as total_buying_amount,
                    ROUND(sum(total_discount),2) as total_discount_amount,
                    ROUND(sum(total_selling_price - total_buying_price),2) as profit,inventories.stock as current_stock,
                    inventories.sku as p_sku,inventories.colour_id,inventories.size_id')
                ->leftJoin('order_details', 'inventories.product_id', '=', 'order_details.product_id')
                ->whereColumn('inventories.product_id', 'order_details.product_id')
                ->whereColumn('inventories.colour_id', 'order_details.colour_id')
                ->whereColumn('inventories.size_id', 'order_details.size_id')
                ->groupBy('p_sku')
                ->groupBy('order_details.product_id')
                ->groupBy('current_stock')
                ->groupBy('inventories.colour_id')
                ->groupBy('inventories.size_id')
                // ->groupBy('inventories.created_at')
                ->orderByDesc('sales_quantity');
                if($from != '' && $to != ''){
                    $data = $data->whereBetween('order_details.created_at', [$from, $to]);
                    // $data = $data->whereHas('product', function ($q) use ($from,$to) {
                    //     $q->whereBetween('created_at', [$from." 00:00:00",$to." 23:59:59"]);
                    // });
                }
                if($category != '' ){
                    $data = $data->whereHas('product', function ($q) use ($category,$subcategory) {
                        $q->where('category_id',$category)
                        ->where('sub_category_id',$subcategory);

                    });
                }
                if($brand != ''){
                    $data = $data->whereHas('product', function ($q) use ($brand) {
                        $q->whereHas('product_brand', function ($q) use ($brand){
                            $q->where('brand_id',$brand);
                        });
                    });
                }
                if($keyword != ''){
                    $data = $data->where('sku','like','%'.$keyword.'%')
                    ->orWhereHas('product', function ($q) use ($keyword) {
                        $q->where('design_code','like','%'.$keyword.'%');
                    });
                }
                //return $data->limit(4)->get();
                $data = $data->paginate($dataQty);
            return response()->json($data);
        } catch (\Throwable $th) {
            // return $th;
            return $this->errorMessage();
        }
    }

    public function campaignReport(Request $request)
    {
            try {
                $from   = $request->get('date_from');
                $to   = $request->get('date_to');
                $to = date('Y-m-d', strtotime("+1 day", strtotime($to)));
                if($request->excel == 'yes'){
                    return \Excel::download(new CampaignReportExport($from,$to),'campaign_report_list.xlsx');
                }
            $dataQty = $request->get('per_page') ? $request->get('per_page') : 12;

            $data = Inventory::with('product.category:id,category_name','product.subcategory:id,category_name',
                'product.product_brand:id,brand_name','product.product_fabric:id,fabric_name','colour:id,color_name',
                'product.product_size:id,size_name','product.product_designer:id,designer_name','product.product_embellishment:id,embellishment_name',
                'product.product_making:id,making_name','product.product_season:id,season_name','product.product_variety:id,variety_name',
                'product.product_fit:id,fit_name','product.product_artist:id,artist_name','product.product_consignment:id,consignment_name',
                'product.product_ingredient:id,ingredient_name','product.campaign:id,campaign_name,campaign_start_date,campaign_expire_date')
                ->selectRaw('order_details.product_id, sum(quantity) as sales_quantity,sum(total_selling_price) as total_selling_amount,
                sum(vat_amount) as total_vat_amount,ROUND(sum(total_buying_price),2) as total_buying_amount,
                ROUND(sum(total_discount),2) as total_discount_amount,
                ROUND(sum(total_selling_price - total_buying_price),2) as profit,inventories.stock as current_stock,
                inventories.sku as p_sku,inventories.colour_id,inventories.size_id')
                ->join('order_details', 'inventories.product_id', '=', 'order_details.product_id')
                ->whereColumn('inventories.product_id', 'order_details.product_id')
                ->whereColumn('inventories.colour_id', 'order_details.colour_id')
                ->whereColumn('inventories.size_id', 'order_details.size_id')
                ->groupBy('p_sku')
                ->groupBy('order_details.product_id')
                ->groupBy('current_stock')
                ->groupBy('inventories.colour_id')
                ->groupBy('inventories.size_id')
                // ->groupBy('inventories.created_at')
                ->orderByDesc('sales_quantity')
                ->whereHas('product', function ($query) {
                     $query->has('campaign');
                });
                if($from != '' && $to != ''){
                    // $data = $data->whereHas('product', function ($q) use ($from,$to) {
                        $data->whereBetween('order_details.created_at', [$from,$to]);
                    // });
                }

                $data = $data->paginate($dataQty);
            return response()->json($data);
        } catch (\Throwable $th) {
            return $th;
            return $this->errorMessage();
        }
    }

    public function paymentReport(Request $request)
    {
        $from   = $request->get('date_from');
        $to   = $request->get('date_to');
        $to = date('Y-m-d', strtotime("+1 day", strtotime($to)));
        $data = DB::table('orders')
            ->selectRaw('IFNULL(payment_method_name, "COD") as gatewayname, sum(total_price) as paid_total')
            ->groupBy('payment_method_name');
            if($from != '' && $to != ''){
                // $data = $data->whereHas('product', function ($q) use ($from,$to) {
                    $data->whereBetween('created_at', [$from,$to]);
                // });
            }
            $data = $data->paginate(10);
        return response()->json($data);
    }

    public function individualCustomerReport(Request $request)
    {
        $byposition   = $request->get('byposition');
        $paymentStatus   = $request->get('payment_status');
        $keyword   = $request->get('keyword');
        $from   = $request->get('from');
        $to   = $request->get('to');
        $to = date('Y-m-d', strtotime("+1 day", strtotime($to)));
        if($request->excel == 'yes'){
            return \Excel::download(new IndividualReportExport($from,$to),'individual_customer_report.xlsx');
        }
        if($request->paymentexcel == 'yes'){
            return \Excel::download(new PaymentReportExport($from,$to),'payment_report.xlsx');
        }
        $dataQty = $request->get('per_page') ? $request->get('per_page') : 12;

        $order = Order::with('user:id,name,phone,address,email')
            ->withCount([
                'order_details as invoice_sum' => function($query) {
                    $query->select(DB::raw('SUM(total_selling_price)'));
                },
                'order_details as refund_count' => function($query) {
                    $query->select(DB::raw('COUNT(is_refunded)'))->where('is_refunded',AllStatic::$active);
                },
                'order_details as refunded_amount' => function($query) {
                    $query->select(DB::raw('SUM(total_selling_price)'))->where('is_refunded',AllStatic::$active);
                }
            ]);

            if($keyword != ''){
                $order = $order->whereHas('user', function ($q) use ($keyword) {
                    $q->where('name','like','%'.$keyword.'%');
                    $q->orWhere('phone','like','%'.$keyword.'%')
                    ->orWhere('email','like','%'.$keyword.'%');
                });
            }

            if($byposition != ''){
                $order = $order->where('order_position',$byposition);
            }

            if($paymentStatus != ''){
                $order = $order->where('payment_status',$paymentStatus);
            }

            if($from != '' && $to != ''){
                $order = $order->whereBetween('order_date',[$from,$to]);
            }
            $order = $order->paginate($dataQty);
        return response()->json($order);
    }

    public function customerRefundReport(Request $request)
    {
        $from   = $request->get('from');
        $to   = $request->get('to');
        $to = date('Y-m-d', strtotime("+1 day", strtotime($to)));
        $keyword   = $request->get('keyword');
        if($request->excel == 'yes'){
            return \Excel::download(new RefundReportExport($from,$to),'customer_refund_report.xlsx');
        }
        $dataQty = $request->get('per_page') ? $request->get('per_page') : 12;

        $order = OrderDetails::with(['user:id,name,phone,address,email','order:id,order_date,total_price,payment_via,payment_method_name'])
                ->where('is_claim_refund',AllStatic::$active)->latest();

            if($from != '' && $to != ''){
                $order = $order->whereBetween('refund_claim_date',[$from,$to]);
            }
            if($keyword != ''){
                $order = $order->whereHas('user', function ($q) use ($keyword) {
                    $q->where('name','like','%'.$keyword.'%');
                    $q->orWhere('phone','like','%'.$keyword.'%')
                    ->orWhere('email','like','%'.$keyword.'%');
                });
            }
            $order = $order->paginate($dataQty);
        return response()->json($order);
    }

    public function customerLifetimeReport(Request $request)
    {
        $from   = $request->get('from');
        $to   = $request->get('to');
        $to = date('Y-m-d', strtotime("+1 day", strtotime($to)));
        $keyword   = $request->get('keyword');
        if($request->excel == 'yes'){
            return \Excel::download(new LifetimeReportExport($from,$to),'customer_lifetime_value_report.xlsx');
        }
        $dataQty = $request->get('per_page') ? $request->get('per_page') : 12;

        $user = User::withCount([
            'order_details as total_spent_amount' => function($query) {
                $query->select(DB::raw('SUM(total_selling_price)'))->where('is_refunded',AllStatic::$inactive);
            },
            'order_details as cancel_count' => function($query) {
                $query->select(DB::raw('COUNT(is_claim_refund)'))->where('is_claim_refund',AllStatic::$active);
            },
            'order_details as refund_count' => function($query) {
                $query->select(DB::raw('COUNT(is_refunded)'))->where('is_refunded',AllStatic::$active);
            },
            'order_details as refunded_amount' => function($query) {
                $query->select(DB::raw('SUM(total_selling_price)'))->where('is_refunded',AllStatic::$active);
            },
            'order as count_order' => function($query) {
                $query->select(DB::raw('COUNT(*)'));
            }
        ])->having('count_order', '>', 0);
        if($from != '' && $to != ''){
            $user = $user->whereBetween('created_at',[$from,$to]);
        }
        if($keyword != ''){
            $user->where('name','like','%'.$keyword.'%')
                ->orWhere('phone','like','%'.$keyword.'%')
                ->orWhere('email','like','%'.$keyword.'%');
        }

        $user = $user->paginate($dataQty);
        return response()->json($user);
    }

    public function makePdf()
    {
        $customPaper = array(0,0,1020,1440);
        $pdf = \PDF::loadView('pages.report.pdf.invoice_report')->setPaper($customPaper, 'portrait');
        return $pdf->download('invoice-report.pdf');

    }
}
