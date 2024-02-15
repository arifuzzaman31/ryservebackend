<?php

namespace App\Http\Controllers;

use App\Http\AllStatic;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator,Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // return response()->json(getParentCategory());
        return view('pages.category.category');
    }


    public function getCategory()
    {
        return view('pages.category.category_add');
    }

    public function editCategory()
    {
        return view('pages.category.edit_category');
    }

    public function getCategoryData(Request $request)
    {
        $perPage = $request->per_page ?? 20;
        $noPagination = $request->get('no_paginate');
        $parentCategory = $request->get('parent_category');
        $keyword   = $request->get('keyword');
        $cate = Category::with('subcategory:id,category_name','composition')->orderBy('parent_category','asc')->orderBy('precedence','asc');
        if($keyword != ''){
            $cate = $cate->where('category_name','like','%'.$keyword.'%');
        }
        if($noPagination != ''){
            if($parentCategory != ''){
                $cate = $cate->where('parent_category',AllStatic::$inactive);
            }
            $cate = $cate->latest()->get();

        } else {
            $cate = $cate->latest()->paginate($perPage);
        }
        return response()->json($cate);
    }

    public function getCategoryByCat(Request $request)
    {
        $alldata = Category::where('status',AllStatic::$active)->orderBy('id')->get();
        $org_data = [];
        foreach($alldata as $cat)
        {
            if($cat->parent_category == 0){
                $org_data[$cat->category_name] = $alldata->where('parent_category',$cat->id);
            }
        }
        return response()->json($org_data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.category.category_add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return response()->json(['status' => 'success', 'message' => $request->all()]);
        $validator = Validator::make($request->all(), [
            'category_name' => 'required',
            'category_image' => 'nullable',
            'parent_category' => 'nullable',
            'video_link' => 'nullable',
        ]);

        try {
            if (!$validator->passes()) {
                return response()->json(['error'=>$validator->errors()->all()]);
            }
            $category = new Category();
            $category->category_name    = $request->category_name;
            $category->slug             = Str::slug($request->category_name);
            $category->parent_category  = $request->parent_category ?? 0;
            $category->category_video   = $request->video_link;
            $category->precedence       = $request->precedence ?? 0;
            $category->status           = $request->status ? 1 : 0;

            // $category->category_image_one   = $request->category_image_one;
            // $category->category_image_one   = $request->category_image_one;
            // $category->category_image_one   = $request->category_image_one;

            $category->save();
            return response()->json(['status' => 'success', 'message' => 'Category Added Successfully!']);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['status' => 'error', 'message' => $th->getMessage()]);
            return response()->json(['status' => 'error', 'message' => 'Something went wrong!']);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        return $category;
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        return view('pages.category.edit_category',['categorydata' => $category]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        try {
            $category->category_image_one = $request->image_one;
            $category->type_one = $request->type_one;
            $category->category_image_two = $request->image_two;
            $category->type_two = $request->type_two;
            $category->category_image_three = $request->image_three;
            $category->type_three = $request->type_three;
            $category->category_feature_image = $request->category_feature_image;
            $category->update();
            return response()->json(['status' => 'success', 'message' => 'Category Media Updated Successfully!']);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'message' => 'Something went wrong!']);
        }

    }

    public function updateCat(Request $request)
    {
        try {
            $category = Category::find($request->id);
            $category->category_name = $request->category_name;
            $category->slug = Str::slug($request->category_name);
            $category->parent_category  = $request->parent_category ?? 0;
            $category->precedence       = $request->precedence ?? 0;
            $category->status           = $request->status ? 1 : 0;
            $category->whats_new        = $request->whats_new;
            $category->update();
            return response()->json(['status' => 'success', 'message' => 'Category Updated Successfully!']);
        } catch (\Throwable $th) {
            // return $th;
            return response()->json(['status' => 'error', 'message' => 'Something went wrong!']);
        }

    }

    public function updateCompCat(Request $request)
    {
        try {
            $category = Category::find($request->cat_id);
            $category->composition()->sync($request->composition);
            return response()->json(['status' => 'success', 'message' => 'Composition Updated Successfully!']);
        } catch (\Throwable $th) {
            return $th;
            return response()->json(['status' => 'error', 'message' => 'Something went wrong!']);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        try {
            if(!empty($category->product)){
                return response()->json(['status' => 'error', 'message' => 'Category Has Many Product!']);
            }
            $category->composition()->detach();
            $category->delete();
            return response()->json(['status' => 'success', 'message' => 'Category Deleted Successfully!']);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'message' =>  $th->getMessage()]);
        }
    }
}
