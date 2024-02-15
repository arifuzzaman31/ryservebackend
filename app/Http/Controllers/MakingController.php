<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Making;
use Str,DB;

class MakingController extends Controller
{
    public $fieldname = 'Making';
    public function index()
    {
        return view('pages.attribute.making.making');
    }

    public function create(Request $request)
    {
        $noPagination = $request->get('no_paginate');
        $dataQty = $request->get('per_page') ? $request->get('per_page') : 10;
        $making = Making::orderBy('id','desc');
        if($noPagination != ''){
            $making = $making->get();
        } else {
            $making = $making->paginate($dataQty);
        }
        return response()->json($making);
    }

    public function store(Request $request)
    {
        $request->validate([
            'making_name' => 'required'
        ]);

        try {
            $making = new Making();
            $making->making_name = $request->making_name;
            $making->slug = Str::slug($request->making_name);
            $making->status = $request->status == true ? 1 : 0;
            $making->save();

            return $this->successMessage($this->fieldname.' Added Successfully !');
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['status' => 'error', 'message' =>  $th->getMessage()]);
        }
    }

    public function update(Request $request,$id)
    {
        $request->validate([
            'making_name' => 'required'
        ]);

        try {
            $updata = Making::find($id);
            $updata->making_name = $request->making_name;
            $updata->slug = Str::slug($request->making_name);
            $updata->status = $request->status == true ? 1 : 0;
            $updata->update();
            
            return $this->successMessage($this->fieldname.' Updated Successfull!');
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'message' =>  $th->getMessage()]);
            //throw $th;
        }
    }

    public function destroy($id)
    {
        try {
            Making::find($id)->delete();
            return $this->successMessage($this->fieldname.' Deleted Successfully!');
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'message' =>  $th->getMessage()]);
        }
    }
}
