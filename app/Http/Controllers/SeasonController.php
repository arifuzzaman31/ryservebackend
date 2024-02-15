<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Season;
use Str,DB;

class SeasonController extends Controller
{
    public $fieldname = 'Season';

    public function index()
    {
        // return "hello";
        return view('pages.attribute.season.season');
    }

    public function create(Request $request)
    {
        $noPagination = $request->get('no_paginate');
        $dataQty = $request->get('per_page') ? $request->get('per_page') : 10;
        $season = Season::orderBy('id','desc');
        if($noPagination != ''){
            $season = $season->get();
        } else {
            $season = $season->paginate($dataQty);
        }
        return response()->json($season);
    }

    public function store(Request $request)
    {
        $request->validate([
            'season_name' => 'required'
        ]);

        try {
            $season = new Season();
            $season->season_name = $request->season_name;
            $season->slug = Str::slug($request->season_name);
            $season->status = $request->status == true ? 1 : 0;
            $season->save();

            return $this->successMessage($this->fieldname.' Added Successfully !');
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['status' => 'error', 'message' =>  $th->getMessage()]);
        }
    }

    public function update(Request $request,$id)
    {
        $request->validate([
            'season_name' => 'required'
        ]);

        try {
            $updata = Season::find($id);
            $updata->season_name = $request->season_name;
            $updata->slug = Str::slug($request->season_name);
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
            Season::find($id)->delete();
            return $this->successMessage($this->fieldname.' Deleted Successfully!');
        } catch (\Throwable $th) {
            return $this->errorMessage();
            return response()->json(['status' => 'error', 'message' =>  $th->getMessage()]);
        }
    }
}
