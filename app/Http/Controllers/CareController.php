<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Care;
use Str,DB;

class CareController extends Controller
{
    public $fieldname = 'Care';
    public function index()
    {
        return view('pages.attribute.care.care');
    }

    public function create(Request $request)
    {
        $noPagination = $request->get('no_paginate');
        $dataQty = $request->get('per_page') ? $request->get('per_page') : 10;
        $care = Care::orderBy('id','desc');
        if($noPagination != ''){
            $care = $care->get();
        } else {
            $care = $care->paginate($dataQty);
        }
        return response()->json($care);
    }

    public function store(Request $request)
    {
        $request->validate([
            'care_name' => 'required'
        ]);

        try {
            $care = new Care();
            $care->care_name = $request->care_name;
            $care->slug = Str::slug($request->care_name);
            $care->status = $request->status == true ? 1 : 0;
            $care->save();

            return $this->successMessage($this->fieldname.' Added Successfully !');
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['status' => 'error', 'message' =>  $th->getMessage()]);
        }
    }

    public function update(Request $request,$id)
    {
        $request->validate([
            'care_name' => 'required'
        ]);

        try {
            $updata = Care::find($id);
            $updata->care_name = $request->care_name;
            $updata->slug = Str::slug($request->care_name);
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
            Care::find($id)->delete();
            return $this->successMessage($this->fieldname.' Deleted Successfully!');
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'message' =>  $th->getMessage()]);
        }
    }
}
