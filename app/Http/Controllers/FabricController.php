<?php

namespace App\Http\Controllers;

use App\Models\Fabric;
use Illuminate\Http\Request;
use DB,Str;

class FabricController extends Controller
{
    public $fieldname = 'Fabric';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.attribute.fabric.fabric');
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
        $fabric = Fabric::orderBy('id','desc');
        if($noPagination != ''){
            $fabric = $fabric->get();
        } else {
            $fabric = $fabric->paginate($dataQty);
        }
        return response()->json($fabric);
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
            'fabric_name' => 'required'
        ]);

        try {
            $fabric = new Fabric();
            $fabric->fabric_name = $request->fabric_name;
            $fabric->fabric_code = $request->fabric_code;
            $fabric->slug = Str::slug($request->fabric_name);
            $fabric->status = $request->status == true ? 1 : 0;
            $fabric->save();

            return response()->json(['status' => 'success', 'message' => $this->fieldname.' Added Successfully !']);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['status' => 'error', 'message' =>  $th->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Fabric  $fabric
     * @return \Illuminate\Http\Response
     */
    public function show(Fabric $fabric)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Fabric  $fabric
     * @return \Illuminate\Http\Response
     */
    public function edit(Fabric $fabric)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Fabric  $fabric
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $request->validate([
            'fabric_name' => 'required'
        ]);

        try {
            $updata = Fabric::find($id);
            $updata->fabric_name = $request->fabric_name;
            $updata->fabric_code = $request->fabric_code;
            $updata->slug = Str::slug($request->fabric_name);
            $updata->status = $request->status == true ? 1 : 0;
            $updata->update();
            
            return response()->json(['status' => 'success', 'message' => $this->fieldname.' Updated Successfully!']);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'message' =>  $th->getMessage()]);
            //throw $th;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Fabric  $fabric
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            Fabric::find($id)->delete();
            return response()->json(['status' => 'success', 'message' => $this->fieldname.' Deleted Successfully!']);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'message' =>  $th->getMessage()]);
        }
    }
}
