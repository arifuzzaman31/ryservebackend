<?php

namespace App\Http\Controllers;

use App\Models\Colour;
use Illuminate\Http\Request;
use DB,Str;

class ColorController extends Controller
{
    public $fieldname = 'Colour';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // return response()->json(Colour::all());
        return view('pages.attribute.colour.colour');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $keyword   = $request->get('keyword');
        $noPagination = $request->get('no_paginate');
        $dataQty = $request->get('per_page') ? $request->get('per_page') : 10;
        $color = Colour::orderBy('id','desc');
        if($keyword != ''){
            $color = $color->orWhere('color_name','like','%'.$keyword.'%');
            $color = $color->orWhere('color_code','like','%'.$keyword.'%');
        }
        if($noPagination != ''){
            $color = $color->get();
        } else {
            $color = $color->paginate($dataQty);
        }
        return response()->json($color);
        //
    }

    public function getColour()
    {
        return response()->json(Colour::orderBy('id','desc'));
        //
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
            'color_name' => 'required',
            'color_code' => 'required'
        ]);

        try {
            $color = new Colour();
            $color->color_name = $request->color_name;
            $color->color_code = $request->color_code;
            $color->slug = Str::slug($request->color_name);
            $color->status = $request->status == true ? 1 : 0;
            $color->save();

            return response()->json(['status' => 'success', 'message' => $this->fieldname.' Added Successfully!']);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['status' => 'error', 'message' =>  $th->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Color  $color
     * @return \Illuminate\Http\Response
     */
    public function show(Colour $color)
    {
        //
        return response()->json(Colour::all());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Color  $color
     * @return \Illuminate\Http\Response
     */
    public function edit(Colour $color)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Color  $color
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $request->validate([
            'color_name' => 'required',
            'color_code' => 'required'
        ]);

        try {
            $updata = Colour::find($id);
            $updata->color_name = $request->color_name;
            $updata->color_code = $request->color_code;
            $updata->slug = Str::slug($request->color_name);
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
     * @param  \App\Models\Color  $color
     * @return \Illuminate\Http\Response
     */
    public function destroy(Colour $color,$id)
    {
        try {
            $color->find($id)->delete();
            return response()->json(['status' => 'success', 'message' => $this->fieldname.' Deleted Successfully!']);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'message' =>  $th->getMessage()]);
        }
    }
}
