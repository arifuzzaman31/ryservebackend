<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ingredients;
use Str,DB;

class IngredientsController extends Controller
{
    public $fieldname = 'Ingredients';
    public function index()
    {
        return view('pages.attribute.ingredients.ingredients');
    }

    public function create(Request $request)
    {
        $noPagination = $request->get('no_paginate');
        $dataQty = $request->get('per_page') ? $request->get('per_page') : 10;
        $ingredient = Ingredients::orderBy('id','desc');
        if($noPagination != ''){
            $ingredient = $ingredient->get();
        } else {
            $ingredient = $ingredient->paginate($dataQty);
        }
        return response()->json($ingredient);
    }

    public function store(Request $request)
    {
        $request->validate([
            'ingredient_name' => 'required'
        ]);

        try {
            $making = new Ingredients();
            $making->ingredient_name = $request->ingredient_name;
            $making->slug = Str::slug($request->ingredient_name);
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
            'ingredient_name' => 'required'
        ]);

        try {
            $updata = Ingredients::find($id);
            $updata->ingredient_name = $request->ingredient_name;
            $updata->slug = Str::slug($request->ingredient_name);
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
            Ingredients::find($id)->delete();
            return $this->successMessage($this->fieldname.' Deleted Successfully!');
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'message' =>  $th->getMessage()]);
        }
    }
}
