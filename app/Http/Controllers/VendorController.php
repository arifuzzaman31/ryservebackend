<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Str;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    public $fieldname = 'Vendor';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.attribute.vendor.vendor');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function create(Request $request)
    {
        $noPagination = $request->get('no_paginate');
        $dataQty = $request->get('per_page') ? $request->get('per_page') : 10;
        $vendor = Vendor::orderBy('id','desc');
        if($noPagination != ''){
            $vendor = $vendor->get();
        } else {
            $vendor = $vendor->paginate($dataQty);
        }
        return response()->json($vendor);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->validate([
            'vendor_name' => 'required'
        ]);

        try {
            $color = new Vendor();
            $color->vendor_name = $request->vendor_name;
            $color->slug = Str::slug($request->vendor_name);
            $color->status = $request->status == true ? 1 : 0;
            $color->save();

            return response()->json(['status' => 'success', 'message' => $this->fieldname.' Added Successfully !']);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['status' => 'error', 'message' =>  $th->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Vendor  $Vendor
     * @return \Illuminate\Http\Response
     */
    public function show(Vendor $Vendor)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Vendor  $Vendor
     * @return \Illuminate\Http\Response
     */
    public function edit(Vendor $Vendor)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Vendor  $Vendor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $request->validate([
            'vendor_name' => 'required'
        ]);

        try {
            $updata = Vendor::find($id);
            $updata->vendor_name = $request->vendor_name;
            $updata->slug = Str::slug($request->vendor_name);
            $updata->status = $request->status == true ? 1 : 0;
            $updata->update();
            
            return response()->json(['status' => 'success', 'message' => $this->fieldname.' Updated Successfull!']);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'message' =>  $th->getMessage()]);
            //throw $th;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Vendor  $Vendor
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            Vendor::find($id)->delete();
            return response()->json(['status' => 'success', 'message' => $this->fieldname.' Deleted Successfully!']);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'message' =>  $th->getMessage()]);
        }
    }
}
