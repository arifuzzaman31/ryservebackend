<?php

namespace App\Http\Controllers;

use App\Http\AllStatic;
use Illuminate\Http\Request;
use App\Models\Campaign;
use App\Models\Discount;
use App\Models\Inventory;
use App\Models\Product;
use DB,Str;

class CampaignController extends Controller
{
    public $fieldname = 'Campaign';

    public function index()
    {
        return view('pages.campaign.campaign');
    }

    public function create()
    {
        return view('pages.campaign.create_campaign');
    }

    public function update(Request $request,$id)
    {
        $request->validate([
            'campaign_name' => 'required',
            'start_at' => 'required',
            'expire_at' => 'required|date|after:start_at'
        ]);
        try{
            $camp = Campaign::find($id);
            $camp->campaign_name = $request->campaign_name;
            $camp->slug = Str::slug($request->campaign_name);
            $camp->campaign_banner_default = $request->campaign_banner;
            $camp->campaign_meta_image = $request->campaign_meta_image;
            $camp->campaign_start_date = $request->start_at;
            $camp->campaign_expire_date = $request->expire_at;
            $camp->status = $request->status == 1 ? 1 : 0;
            $camp->save();
         return response()->json(['status' => 'success', 'message' => $this->fieldname.' Updated Successfully!']);
    }catch (\Throwable $th) {
        return response()->json(['status' => 'error', 'message' => $th]);
    }

    }

    public function storeProductSkuDiscount(Request $request)
    {
        try {
            // return response()->json($request->all());
            foreach ($request->product_sku as $value) {
                $discStatus = $value['discount_type'] == 'remove' ? 0 : 1;
                if($value['discount_type'] == 'remove'){
                    Discount::where('disc_sku',$value['sku'])->delete();
                } else {
                    Discount::updateOrCreate([
                         'product_id'=> $request->id,
                         'disc_sku' =>  $value['sku']
                     ],[
                         'discount_amount' => $value['discount'] ?? 0,
                         'discount_type'   => $value['discount_type'],
                         'type'            => $value['type'],
                         'max_amount'      => $value['discount_type'] == 'percentage' ? $value['max_amount'] : NULL,
                         'status'          => AllStatic::$active
                     ]);
                }
                Inventory::where('sku',$value['sku'])->update([
                    'disc_status' => $discStatus
                ]);
            }
            return response()->json(['status' => 'success', 'message' => 'Discount affected Successfully!']);
        } catch (\Throwable $th) {
            return $th;
            return response()->json(['status' => 'error', 'message' => $th]);
        }
    }

    public function getCampProduct($id)
    {
        $camp = Campaign::find($id);
        return view('pages.campaign.campaign_product',['campaigndata' => $camp]);
    }

    public function getProductByCampId(Request $request,$camp_id)
    {
        $noPagination = $request->get('no_paginate');
        $keyword   = $request->get('keyword');
        $category   = $request->get('category');
        $subcategory   = $request->get('subcategory');
        $dataQty = $request->get('per_page') ? $request->get('per_page') : 12;

        $product = Product::join('campaign_products', 'products.id', '=', 'campaign_products.product_id')
        ->where('campaign_products.campaign_id', $camp_id)
        // ->where('products.id', 'campaign_products.product_id')
        ->with(['campaign','vat', 'category:id,category_name',
        'subcategory', 'inventory.discount', 'product_size', 'product_colour'])
        ->select(['products.*','campaign_products.campaign_id']);


        // $allCompanies = Products::with(['company' => function($q) {
        //     $q->where('visible', 0);
        // }])


        // $product = $campaign->product;

        if ($keyword != '') {
            $product = $product->whereHas('inventory', function ($q) use ($keyword) {
                $q->where('sku', 'like', '%' . $keyword . '%');
            });
            $product = $product->orWhere('product_name', 'like', '%' . $keyword . '%');
            $product = $product->orWhere('design_code', 'like', '%' . $keyword . '%');
        }

        $product = $product->paginate($dataQty);
        return $product;
    }

    public function getCampaing(Request $request)
    {
        $noPagination = $request->get('no_paginate');
        $status = $request->get('status');
        $dataQty = $request->get('per_page') ? $request->get('per_page') : 10;
        $campaign = Campaign::orderBy('id','desc');
        if($status != ''){
            $campaign = $campaign->where('status',AllStatic::$active);
        }
        if($noPagination != ''){
            $campaign = $campaign->get();
        } else {
            $campaign = $campaign->paginate($dataQty);
        }
        return response()->json($campaign);
    }

    public function store(Request $request)
    {
        $request->validate([
            'campaign_name' => 'required',
            'start_at' => 'required',
            'expire_at' => 'required|date|after:start_at'
        ]);

        try{
            $camp = new Campaign();
            $camp->campaign_name = $request->campaign_name;
            $camp->slug = Str::slug($request->campaign_name);
            $camp->campaign_banner_default = $request->campaign_banner;
            $camp->campaign_meta_image = $request->campaign_meta_image;
            $camp->campaign_start_date = $request->start_at;
            $camp->campaign_expire_date = $request->expire_at;
            $camp->status = AllStatic::$active;
            $camp->save();

            return response()->json(['status' => 'success', 'message' => $this->fieldname.' Created Successfully!']);
        }catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'message' => $th]);
        }
    }
    public function storeAddtoCamp(Request $request)
    {
        $request->validate([
            'campaign' => 'required',
            'type' => 'required',
            // 'discount_type' => 'required',
            // 'discount_amount' => 'required',[144,142,181,146,149,155,151,143,147]
            'product' => 'required|array|min:1'
        ]);

        try{
            DB::beginTransaction();
            if($request->type == 'campaign'){
                $camp = Campaign::find($request->campaign);
                $camp->product()->syncWithoutDetaching($request->product);
            }else{
                $sect = DB::table('pages')->where('id',$request->campaign);
                $gt = $sect->get()->first();
                $ids = $gt->product_id != NULL ? array_merge(json_decode($gt->product_id),$request->product) : $request->product;
                $sect->update([
                    'product_id' => json_encode(array_values(array_unique($ids)))
                ]);
            }
            // $camp->product()->sync($request->product);
            // $camp->save();

            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Product Added to '.$request->type]);
        }catch (\Throwable $th) {
            DB::rollback();
            return $th;
            return response()->json(['status' => 'error', 'message' => $th]);
        }
    }

    public function removeCampProduct(Request $request)
    {
        try {
            DB::beginTransaction();
            Discount::where('type','campaign')
            ->whereIn('product_id',$request->product)->delete();
            $camp = Campaign::find($request->camp_id);
            $camp->product()->detach($request->product);
            DB::commit();
            return $this->successMessage("Product Has been removed!");
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollback();
            return $this->errorMessage();
        }
    }

    public function destroy(Campaign $campaign)
    {
        try {
            DB::beginTransaction();
            Discount::where('type','campaign')
            ->whereIn('product_id',$campaign->product()->pluck('product_id')->toArray())->delete();
            $campaign->product()->detach();
            $campaign->product()->pluck('product_id')->toArray();
            $campaign->delete();
            //code...
            DB::commit();
            return $this->successMessage("Campaign removed with Prodcut!");
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->errorMessage();
            //throw $th;
        }
    }
}
