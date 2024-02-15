<?php

namespace App\Http\Controllers;

use App\Models\MediaManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MediaManagerController extends Controller
{
    public $fieldname = 'Media';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.media.media');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $dataQty = $request->get('per_page') ? $request->get('per_page') : 12;
        $keyword   = $request->get('keyword');
        $type   = $request->get('type');
        $imgdata = DB::table('media_managers')->orderBy('created_at','desc');

        if($type != ''){
            $imgdata = $imgdata->where('file_type',$type);
        }
        if($keyword != ''){
            $imgdata = $imgdata->where('product_name','like','%'.$keyword.'%');
            $imgdata = $imgdata->orWhere('extension','like','%'.$keyword.'%');
        }

        return response()->json($imgdata->paginate($dataQty));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            // return $request->imgs;
            foreach($request->imgs as $value)
            {
                DB::table('media_managers')->insert([
                    'file_link' => 'https://res.cloudinary.com/diyc1dizi/'.$value['resource_type'].'/'.'upload/'.$value['public_id'].'.'.$value['format'],
                    'file_type' => $value['resource_type'],
                    'product_name' =>  array_values(array_slice(explode('/',$value['public_id']), -1))[0],
                    'cld_public_id' =>  $value['public_id'],
                    'extension' => $value['format'],
                    'created_at' => now()
                ]);
            }
            return $this->successMessage($this->fieldname.' Added Successfully !');
        } catch (\Throwable $th) {
            return $th;
            return response()->json(['status' => 'error', 'message' =>  $th->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MediaManager  $mediaManager
     * @return \Illuminate\Http\Response
     */
    public function show(MediaManager $mediaManager)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MediaManager  $mediaManager
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MediaManager  $mediaManager
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MediaManager $mediaManager)
    {
        //
        try {
            DB::table('media_managers')->whereIn('cld_public_id',$request->imgs)->delete();
            return $this->successMessage('Media Deleted Successfully!');
        } catch (\Throwable $th) {
            return $this->errorMessage();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MediaManager  $mediaManager
     * @return \Illuminate\Http\Response
     */
    public function destroy(MediaManager $mediaManager)
    {
        try {
            $mediaManager->delete();
            return $this->successMessage('Media Deleted Successfully!');
        } catch (\Throwable $th) {
            return $this->errorMessage();
        }
    }
}
