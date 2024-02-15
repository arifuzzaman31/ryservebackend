<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\AllStatic;
use App\Models\Campaign;
use App\Http\Resources\CampaignResource;
use Illuminate\Support\Facades\DB;

class FrontController extends Controller
{
    public function getCampaing(Request $request)
    {
        $status = $request->get('status');
        $campaign = Campaign::where('campaign_expire_date','>=',date('Y-m-d'))->orderBy('id','desc');
        $campaign = $campaign->where('status',AllStatic::$active);
        // if($status != ''){
        // }
        $campaign = $campaign->get();

        return CampaignResource::collection($campaign);
    }

    public function getCategoryFabric($cat_id)
    {
        return DB::table('fabrics')
            ->join('category_fabric','fabrics.id','category_fabric.fabric_id')
            ->where('category_id',$cat_id)
            ->get();
    }

    public function shippingData()
    {
        $data = DB::table('shipping_configs')
            ->where('status',AllStatic::$active)
            ->get();
        return response()->json($data);
    }

    public function allAttribute($category_id)
    {
        return response()->json([
            'fabrics' => DB::table('fabrics')->join('category_fabric', function($join) {
                $join->on('fabrics.id', '=', 'category_fabric.fabric_id');
              })->where('category_id',$category_id)->where('status',AllStatic::$active)->get(),
            'sizes' => DB::table('sizes')->select('id','size_name','status')->where('status',AllStatic::$active)->get(),
            'colours' => DB::table('colours')->select('id','color_name','status')->where('status',AllStatic::$active)->get()
        ]);
    }

}
