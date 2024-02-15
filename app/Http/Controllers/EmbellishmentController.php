<?php

namespace App\Http\Controllers;

use App\Models\Embellishment;
use Illuminate\Http\Request;
use Str;

class EmbellishmentController extends Controller
{
    public $fieldname = 'Embellishment';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.attribute.embellishment.embellishment');
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
        $embellishment = Embellishment::orderBy('id','desc');
        if($noPagination != ''){
            $embellishment = $embellishment->get();
        } else {
            $embellishment = $embellishment->paginate($dataQty);
        }
        return response()->json($embellishment);
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
            'embellishment_name' => 'required'
        ]);

        try {
            $embellishment = new Embellishment();
            $embellishment->embellishment_name = $request->embellishment_name;
            $embellishment->slug = Str::slug($request->embellishment_name);
            $embellishment->status = $request->status == true ? 1 : 0;
            $embellishment->save();

            return $this->successMessage($this->fieldname.' Added Successfully !');
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['status' => 'error', 'message' =>  $th->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Embellishment  $embellishment
     * @return \Illuminate\Http\Response
     */
    public function show(Embellishment $embellishment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Embellishment  $embellishment
     * @return \Illuminate\Http\Response
     */
    public function edit(Embellishment $embellishment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Embellishment  $embellishment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $request->validate([
            'embellishment_name' => 'required'
        ]);

        try {
            $updata = Embellishment::find($id);
            $updata->embellishment_name = $request->embellishment_name;
            $updata->slug = Str::slug($request->embellishment_name);
            $updata->status = $request->status == true ? 1 : 0;
            $updata->update();
            
            return $this->successMessage($this->fieldname.' Updated Successfull!');
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'message' =>  $th->getMessage()]);
            //throw $th;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Embellishment  $embellishment
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            Embellishment::find($id)->delete();
            return $this->successMessage($this->fieldname.' Deleted Successfully!');
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'message' =>  $th->getMessage()]);
        }
    }
}
