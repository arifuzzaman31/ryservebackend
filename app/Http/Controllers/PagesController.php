<?php

namespace App\Http\Controllers;

use App\Exports\AttributeExport;
use App\Http\AllStatic;
use App\Models\AttributeValue;
use App\Models\ShippingConfig;
use App\Models\Page;
use App\Models\Product;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Traits\ProductTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PagesController extends Controller
{
    use ProductTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function homePage()
    {
        return view('pages.page.homepage');
    }

    public function storeSection(Request $request)
    {
        // return response()->json($request->all());
        try {
            $hp = new Page();
            $hp->section_name = Str::slug($request->section_name);
            $hp->banner = json_encode($request->banner);
            $hp->pattern = $request->pattern;
            $hp->use_for = $request->use_for;
            $hp->precedence = $request->precedence;
            $hp->status = $request->status ?? 0;
            $hp->save();
            return response()->json(['status' => 'success', 'message' => 'Home Section Created Successfully!']);
        } catch (\Throwable $th) {
            return $th;
        }
    }

    public function singleSection(Request $request,$id)
    {
        // return response()->json($request->all());
        try {
            $hp = Page::find($id);
            // $product = Product::whereIn('id',json_decode($hp->product_id))->get();
            return view('pages.page.edit_page',['sectionData' => $hp]);
        } catch (\Throwable $th) {
            return $th;
        }
    }

    public function update(Request $request,$id)
    {
        try {
            DB::table('pages')->where('id',$id)->update([
                'section_name'  => Str::slug($request->section_name),
                'status'    =>  $request->status == true ? 1 : 0
            ]);
            return response()->json(['status' => 'success', 'message' => 'Section Updated Successfully!']);
        } catch (\Throwable $th) {
            return $this->errorMessage();
        }
    }

    public function homeImageUpdate(Request $request)
    {
        // return response()->json($request->all());
        try {
            $hp = new Page();
            $hp->section_name = $request->section_name;
            $hp->banner = $request->banner;
            $hp->pattern = $request->pattern;
            $hp->use_for = $request->use_for;
            $hp->precedence = $request->precedence;
            $hp->status = $request->status ?? 0;
            $hp->save();
            return response()->json(['status' => 'success', 'message' => 'Home Page Updated Successfully!']);
        } catch (\Throwable $th) {
            return $this->errorMessage();
        }
    }

    public function getPageSection(Request $request)
    {
        $noPagination = $request->get('no_paginate');
        $status = $request->get('status');
        $dataQty = $request->get('per_page') ? $request->get('per_page') : 10;
        $pagesData = Page::orderBy('id','asc');
        if($status != ''){
            $pagesData = $pagesData->where('status',AllStatic::$active);
        }
        if($noPagination != ''){
            $pagesData = $pagesData->get();
        } else {
            $pagesData = $pagesData->paginate($dataQty);
        }
        return response()->json($pagesData);
    }

    public function sectionProductRemove(Request $request)
    {
        try {
            // return $request->product_id;[144,147,149,142,181,184]
            $page = Page::find($request->section_id);
            $prev = array_diff(json_decode($page->product_id),$request->product_id);
            $page->product_id = json_encode(array_values($prev));
            $page->update();
            return $this->successMessage("Product Has been removed!");
        } catch (\Throwable $th) {
            // return $th;
            return $this->errorMessage();
        }
    }

    public function deletePageSection($id)
    {
        try {
            Page::find($id)->delete();
            return $this->successMessage("Page Section Deleted!");
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'message' =>  $th->getMessage()]);
        }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function exportAllAttr()
    {
        // return $this->getBrand()->toArray();
        $plate = [
            // 'category' => DB::table('categories')->select('id', 'category_name', 'parent_category', 'status')->get()->toArray(),
            'brand'    => DB::table('brands')->select('id', 'brand_name', 'status')->get()->toArray(),
            'artist'  => DB::table('artists')->select('id', 'artist_name', 'status')->get()->toArray(),
            'care'    => DB::table('cares')->select('id', 'care_name', 'status')->get()->toArray(),
            'color'    => DB::table('colours')->select('id', 'color_name', 'status')->get()->toArray(),
            'consignment'    => DB::table('consignments')->select('id', 'consignment_name', 'status')->get()->toArray(),
            'designer'    => DB::table('designers')->select('id', 'designer_name', 'status')->get()->toArray(),
            'embellishment'    => DB::table('embellishments')->select('id', 'embellishment_name', 'status')->get()->toArray(),
            'fabric'    => DB::table('fabrics')->select('id', 'fabric_name', 'status')->get()->toArray(),
            'fit'    => DB::table('fits')->select('id', 'fit_name', 'status')->get()->toArray(),
            'ingredient'    => DB::table('ingredients')->select('id', 'ingredient_name', 'status')->get()->toArray(),
            'making'    => DB::table('makings')->select('id', 'making_name', 'status')->get()->toArray(),
            'season'    => DB::table('seasons')->select('id', 'season_name', 'status')->get()->toArray(),
            'size'    => DB::table('sizes')->select('id', 'size_name', 'status')->get()->toArray(),
            'variety'    => DB::table('varieties')->select('id', 'variety_name', 'status')->get()->toArray(),
            'vendor'    => DB::table('vendors')->select('id', 'vendor_name', 'status')->get()->toArray(),
            'tax'    => DB::table('vat_taxes')->select('id', 'tax_percentage as tax_name')->get()->toArray(),
        ];

        $export = new AttributeExport($plate);
        return Excel::download($export, 'attribute-list.xlsx');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getShipping()
    {
        return view('pages.page.shipping');
    }

    public function storeShippingCharge(Request $request)
    {
        $request->validate([
            'country_name'  => 'required|unique:shipping_configs,country_name,'.$request->id
        ]);

        $cntry = explode("code",$request->country_name);

         $data = ['amount' => 0];

        // if(trim($cntry[0]) == 'Bangladesh'){
        //     $data = [
        //         'inside_city'   => [
        //             'pathao'    => $request->inside_city_pathao,
        //             'e_courier' => $request->inside_city_e_courier
        //         ],
        //         'outside_city'  => [
        //             'pathao'    => $request->outside_city_pathao,
        //             'e_courier' => $request->outside_city_e_courier
        //         ],
        //     ];
        // } else {
        //     $data = ['amount' => $request->amount];
        // }


        try {
            ShippingConfig::updateOrCreate([
                'id'    =>  $request->id
            ],[
                'country_name'  => trim($cntry[0]),
                'country_code'  => trim($cntry[1]),
                'shipping_charge' => json_encode($data),
                'status'    => $request->status ? 1 : 0
            ]);

            return $this->successMessage("Shipping Charge Added!");
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'message' =>  $th->getMessage()]);
        }
    }

    public function deleteShipping($id)
    {
        try {
            DB::table('shipping_configs')->where('id',$id)->delete();
            return $this->successMessage("Shipping Charge Added!");
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'message' =>  $th->getMessage()]);
        }
    }

    public function getShippingData(Request $request)
    {
        $dataQty = $request->get('per_page') ? $request->get('per_page') : 12;
        $keyword   = $request->get('keyword');
        $shipp = DB::table('shipping_configs')->orderBy('country_name','asc');
        if($keyword != ''){
            $shipp = $shipp->where('country_name','like','%'.$keyword.'%');
        }

        return $shipp->paginate($dataQty);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AttributeValue  $attributeValue
     * @return \Illuminate\Http\Response
     */
    public function getInformation()
    {
        $cont = DB::table('informations')->get();
        return view('pages.page.information',['content' => $cont]);
    }


    public function storeInformation(Request $request)
    {
        try {
            DB::table('informations')
                ->updateOrInsert(
                    ['id'    =>  $request->id],
                    [
                        'title'    => $request->title,
                        'slug'     => Str::slug($request->title),
                        'back_link'  => config('app.front_url').Str::slug($request->title),
                        'content'  => $request->content,
                        'status'   => $request->status,
                        'created_at' => now()
                    ]
                );

            return response()->json([
                'status' => 'success',
                'message' => 'Information Added Successfully!'
            ], 200);

        } catch (\Throwable $th) {
            return $this->errorMessage();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AttributeValue  $attributeValue
     * @return \Illuminate\Http\Response
     */
    public function deleteInformation($id)
    {
        try {
            DB::table('informations')->where('id',$id)->delete();
            return $this->successMessage('Information Deleted Successfully!');
        } catch (\Throwable $th) {
            return $this->errorMessage();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AttributeValue  $attributeValue
     * @return \Illuminate\Http\Response
     */
    public function destroy(AttributeValue $attributeValue)
    {
        //
    }
}
