<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Variety;
use Str,DB;

class VarietyController extends Controller
{
    public $fieldname = 'Variety';

    public function index()
    {
        // return "hello";
        return view('pages.attribute.variety.variety');
    }

    public function create(Request $request)
    {
        $noPagination = $request->get('no_paginate');
        $dataQty = $request->get('per_page') ? $request->get('per_page') : 10;
        $variety = Variety::orderBy('id','desc');
        if($noPagination != ''){
            $variety = $variety->get();
        } else {
            $variety = $variety->paginate($dataQty);
        }
        return response()->json($variety);
    }

    public function store(Request $request)
    {
        $request->validate([
            'variety_name' => 'required'
        ]);

        try {
            $variety = new Variety();
            $variety->variety_name = $request->variety_name;
            $variety->slug = Str::slug($request->variety_name);
            $variety->status = $request->status == true ? 1 : 0;
            $variety->save();

            return $this->successMessage($this->fieldname.' Added Successfully !');
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['status' => 'error', 'message' =>  $th->getMessage()]);
        }
    }

    public function update(Request $request,$id)
    {
        $request->validate([
            'variety_name' => 'required'
        ]);

        try {
            $updata = Variety::find($id);
            $updata->variety_name = $request->variety_name;
            $updata->slug = Str::slug($request->variety_name);
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
            Variety::find($id)->delete();
            return $this->successMessage($this->fieldname.' Deleted Successfully!');
        } catch (\Throwable $th) {
            return $this->errorMessage();
            return response()->json(['status' => 'error', 'message' =>  $th->getMessage()]);
        }
    }
}
