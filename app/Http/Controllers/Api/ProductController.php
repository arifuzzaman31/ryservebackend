<?php

namespace App\Http\Controllers\Api;

use App\Http\AllStatic;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\CampaignProduct;
use App\Models\Product;
use AmrShawky\LaravelCurrency\Facade\Currency;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $noPagination = $request->get('no_paginate');
        // $byProduct = $request->get('by_product');
        $discount   = $request->get('discount');
        $keyword   = $request->get('keyword');
        $tak_some   = $request->get('take_some');
        $q_category   = $request->get('category');
        $q_sub_category = $request->get('sub_category');
        $camp_id   = $request->get('camp_id');
        $attrname   = $request->get('attrname');
        $attrid   = $request->get('attrid');
        $whatsNew  = $request->get('whats_new');
        $pricerange   = $request->get('range');
        $dataQty = $request->get('per_page') ? $request->get('per_page') : 12;

        $product = Product::with(['category:id,category_name,slug','subcategory','product_fabric',
        'product_size','product_colour','inventory.discount']);

        if($camp_id != ''){
            $product =  $product->with('campaign')
            ->whereHas('campaign', function($q) use ($camp_id) {
                $q->where('campaign_id', $camp_id);
            });
            // $product = $product->join('campaign_products','products.id','campaign_products.product_id')
            //     ->select('products.*','campaign_products.campaign_id','campaign_products.product_id')
            //     ->where('campaign_id',$camp_id);
            if($noPagination != ''){
                $product = $product->get();
            } else {
                $product = $product->paginate($dataQty);
            }
            return ProductResource::collection($product);
        }

        if($q_category != ''){
            if($q_sub_category != ''){
                $product =  $product->where(['category_id' => $q_category, 'sub_category_id' => $q_sub_category]);
            }else{
                $product =  $product->where('category_id',$q_category);
            }
        }

        if($discount != ''){
            $product = $product->where('is_discount',0);
        }

        if($attrname != '' && $attrid != ''){
            $product = $product->whereHas('product_'.$attrname, function ($q) use ($attrname,$attrid) {
                $q->where($attrname.'_id', $attrid);
            });
        }

        if($keyword != ''){
            $product = $product->where('product_name','like','%'.$keyword.'%');
            $product = $product->orWhere('design_code','like','%'.$keyword.'%');
        }

        if($pricerange != ''){
            $rang = explode('-',$pricerange);
            $product = $product->whereHas('inventory', function ($q) use ($rang) {
                $q->whereBetween('mrp', [$rang[0], $rang[1]]);
            });
        }

        if($tak_some != ''){
            $product = $product->latest()->take($tak_some);
        }

        if($whatsNew != ''){
            $product = $product->where('is_new',AllStatic::$active);
        }

        if($noPagination != ''){

            $product = $product->where('status',AllStatic::$active)->latest()->get();

        } else {
            $product = $product->where('status',AllStatic::$active)->latest()->paginate($dataQty);
        }
        // return $product;
        return ProductResource::collection($product);
    }

    public function getProductByCat(Request $request,$cat,$subcat = null)
    {
        try {
            //code...
            $noPagination = $request->get('no_paginate');
            $dataQty = $request->get('per_page') ? $request->get('per_page') : 12;
            $attrname   = $request->get('attrname');
            $attrid   = $request->get('attrid');
            $pid   = $request->get('p_id');
            $pricerange   = $request->get('range');
            $tak_some   = $request->get('take_some');

            $product = Product::with(['category:id,category_name,slug','subcategory','product_fabric',
            'product_size','product_colour','inventory.discount'])
                        ->where('category_id',$cat)
                        ->orderBy('id','desc');
                    if($subcat != '' && $subcat != null){
                        $product = $product->where('sub_category_id',$subcat);
                    }
                    if($pid != ''){
                        $product = $product->where('id','!=',$pid);
                    }
            if($attrname != '' && $attrid != ''){
                $product = $product->whereHas('product_'.$attrname, function ($q) use ($attrname,$attrid) {
                    $q->where($attrname.'_id', $attrid);
                });
            }

            if($pricerange != ''){
                $rang = explode('-',$pricerange);
                $product = $product->whereHas('inventory', function ($q) use ($rang) {
                    $q->whereBetween('mrp', [$rang[0], $rang[1]]);
                });
            }

            if($noPagination != ''){
                if($tak_some != ''){
                    $product = $product->latest()->take($tak_some);
                }
                $product = $product->get();
            } else {
                $product = $product->paginate($dataQty);
            }

            return ProductResource::collection($product);
        } catch (\Throwable $th) {
            return $this->errorMessage();
        }

    }

    public function getProductById(Request $request,$id)
    {
        try {
            //code...
            $product = Product::with(['category:id,category_name,slug','subcategory','product_fabric:id,fabric_name,fabric_code,slug','inventory.discount',
                'product_size','product_colour',
                'vat:id,tax_name,tax_percentage','product_vendor:id,vendor_name,slug','product_brand:id,brand_name,slug','product_designer:id,designer_name,slug','product_embellishment:id,embellishment_name,slug',
                'product_making:id,making_name,slug','product_season:id,season_name,slug','product_variety','product_fit:id,fit_name,slug','product_artist','product_consignment',
                'product_ingredient:id,ingredient_name,slug','product_care','tag:id,product_id,keyword_name'])->find($id);
            return new ProductResource($product);
        } catch (\Throwable $th) {
            // return $th;
            return $this->errorMessage();
        }
    }
}
