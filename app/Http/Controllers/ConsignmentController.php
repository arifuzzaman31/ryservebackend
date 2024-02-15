<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Consignment;
use Str,DB;

class ConsignmentController extends Controller
{
    public $fieldname = 'Consignment';
    public function index()
    {
        return view('pages.attribute.consignment.consignment');
    }

    public function create(Request $request)
    {
        $noPagination = $request->get('no_paginate');
        $dataQty = $request->get('per_page') ? $request->get('per_page') : 10;
        $making = Consignment::orderBy('id','desc');
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
            'consignment_name' => 'required'
        ]);

        try {
            $making = new Consignment();
            $making->consignment_name = $request->consignment_name;
            $making->slug = Str::slug($request->consignment_name);
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
            'consignment_name' => 'required'
        ]);

        try {
            $updata = Consignment::find($id);
            $updata->consignment_name = $request->consignment_name;
            $updata->slug = Str::slug($request->consignment_name);
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
            Consignment::find($id)->delete();
            return $this->successMessage($this->fieldname.' Deleted Successfully!');
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'message' =>  $th->getMessage()]);
        }
    }
}
