<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VatTax;
use Str,DB;

class CompanyController extends Controller
{
    public $fieldname = 'VAT';
    public function index()
    {
        return view('pages.company.tax');
    }

    public function create(Request $request)
    {
        $noPagination = $request->get('no_paginate');
        $dataQty = $request->get('per_page') ? $request->get('per_page') : 10;
        $vattax = VatTax::orderBy('id','desc');
        if($noPagination != ''){
            $vattax = $vattax->get();
        } else {
            $vattax = $vattax->paginate($dataQty);
        }
        return response()->json($vattax);
    }

    public function store(Request $request)
    {
        $request->validate([
            'tax_name' => 'required',
            'tax_percentage' => 'required'
        ]);

        try {
            $vattax = new VatTax();
            $vattax->tax_name = $request->tax_name;
            $vattax->tax_percentage = $request->tax_percentage;
            $vattax->status = $request->status == true ? 1 : 0;
            $vattax->save();

            return $this->successMessage($this->fieldname.' Added Successfully !');
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['status' => 'error', 'message' =>  $th->getMessage()]);
        }
    }

    public function update(Request $request,$id)
    {
        $request->validate([
            'tax_name' => 'required',
            'tax_percentage' => 'required'
        ]);

        try {
            $vattax = VatTax::find($id);
            $vattax->tax_name = $request->tax_name;
            $vattax->tax_percentage = $request->tax_percentage;
            $vattax->status = $request->status == true ? 1 : 0;
            $vattax->update();
            
            return $this->successMessage($this->fieldname.' Updated Successfull!');
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'message' =>  $th->getMessage()]);
            //throw $th;
        }
    }

    public function destroy($id)
    {
        try {
            VatTax::find($id)->delete();
            return $this->successMessage($this->fieldname.' Deleted Successfully!');
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'message' =>  $th->getMessage()]);
        }
    }
}
