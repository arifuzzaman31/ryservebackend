<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fit;
use Str,DB;

class FitController extends Controller
{
    public $fieldname = 'Fit';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.attribute.fit.fit');
    }

    public function create(Request $request)
    {
        $noPagination = $request->get('no_paginate');
        $dataQty = $request->get('per_page') ? $request->get('per_page') : 10;
        $fit = Fit::orderBy('id','desc');
        if($noPagination != ''){
            $fit = $fit->get();
        } else {
            $fit = $fit->paginate($dataQty);
        }
        return response()->json($fit);
    }

    public function store(Request $request)
    {
        $request->validate([
            'fit_name' => 'required'
        ]);

        try {
            $fit = new Fit();
            $fit->fit_name = $request->fit_name;
            $fit->slug = Str::slug($request->fit_name);
            $fit->status = $request->status == true ? 1 : 0;
            $fit->save();

            return response()->json(['status' => 'success', 'message' => $this->fieldname.' Added Successfully !']);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['status' => 'error', 'message' =>  $th->getMessage()]);
        }
    }

   
    public function show(Fit $fit)
    {
        //
    }

   
    public function edit(Fit $fit)
    {
        //
    }

   
    public function update(Request $request,$id)
    {
        $request->validate([
            'fit_name' => 'required'
        ]);

        try {
            $updata = Fit::find($id);
            $updata->fit_name = $request->fit_name;
            $updata->slug = Str::slug($request->fit_name);
            $updata->status = $request->status == true ? 1 : 0;
            $updata->update();
            
            return response()->json(['status' => 'success', 'message' => $this->fieldname.' Updated Successfull!']);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'message' =>  $th->getMessage()]);
            //throw $th;
        }
    }

    
    public function destroy($id)
    {
        try {
            Fit::find($id)->delete();
            return response()->json(['status' => 'success', 'message' => $this->fieldname.' Deleted Successfully!']);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'message' =>  $th->getMessage()]);
        }
    }
}
