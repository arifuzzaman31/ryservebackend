<?php

namespace App\Http\Controllers;

use App\Models\Designer;
use Illuminate\Http\Request;
use Str;

class DesignerController extends Controller
{
    public $fieldname = 'Designer';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.attribute.designer.designer');
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
        $designer = Designer::orderBy('id','desc');
        if($noPagination != ''){
            $designer = $designer->get();
        } else {
            $designer = $designer->paginate($dataQty);
        }
        return response()->json($designer);
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
            'designer_name' => 'required',
            'designer_sort_name' => 'required'
        ]);

        try {
            $designer = new Designer();
            $designer->designer_name = $request->designer_name;
            $designer->designer_sort_name = $request->designer_sort_name;
            $designer->slug = Str::slug($request->designer_name);
            $designer->status = $request->status == true ? 1 : 0;
            $designer->save();

            return response()->json(['status' => 'success', 'message' => $this->fieldname.' Added Successfully !']);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['status' => 'error', 'message' =>  $th->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Designer  $designer
     * @return \Illuminate\Http\Response
     */
    public function show(Designer $designer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Designer  $designer
     * @return \Illuminate\Http\Response
     */
    public function edit(Designer $designer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Designer  $designer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $request->validate([
            'designer_name' => 'required',
            'designer_sort_name' => 'required'
        ]);

        try {
            $updata = Designer::find($id);
            $updata->designer_name = $request->designer_name;
            $updata->designer_sort_name = $request->designer_sort_name;
            $updata->slug = Str::slug($request->designer_name);
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
     * @param  \App\Models\Designer  $designer
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            Designer::find($id)->delete();
            return response()->json(['status' => 'success', 'message' => $this->fieldname.' Deleted Successfully!']);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'message' =>  $th->getMessage()]);
        }
    }
}
