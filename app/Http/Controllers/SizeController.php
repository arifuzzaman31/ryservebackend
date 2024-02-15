<?php

namespace App\Http\Controllers;

use App\Models\Size;
use Illuminate\Http\Request;
use DB,Str;

class SizeController extends Controller
{
    public $fieldname = 'Size';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.attribute.size.size');
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
        $size = Size::orderBy('id','desc');
        if($noPagination != ''){
            $size = $size->get();
        } else {
            $size = $size->paginate($dataQty);
        }
        return response()->json($size);
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
            'size_name' => 'required'
        ]);

        try {
            $color = new Size();
            $color->size_name = $request->size_name;
            $color->slug = Str::slug($request->size_name);
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
     * @param  \App\Models\Size  $size
     * @return \Illuminate\Http\Response
     */
    public function show(Size $size)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Size  $size
     * @return \Illuminate\Http\Response
     */
    public function edit(Size $size)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Size  $size
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $request->validate([
            'size_name' => 'required'
        ]);

        try {
            $updata = Size::find($id);
            $updata->size_name = $request->size_name;
            $updata->slug = Str::slug($request->size_name);
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
     * @param  \App\Models\Size  $size
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            Size::find($id)->delete();
            return response()->json(['status' => 'success', 'message' => $this->fieldname.' Deleted Successfully!']);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'message' =>  $th->getMessage()]);
        }
    }
}
