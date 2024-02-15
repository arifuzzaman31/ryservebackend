<?php

namespace App\Http\Controllers\Api;

use App\Http\AllStatic;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function categoryData($id)
    {
        try {
            $category = Category::with('children')->find($id);
            return new CategoryResource($category);
        } catch (\Throwable $th) {
            // return $th;
            return $this->errorMessage();
            // return \DB::table('categories')->select(['id','category_name','parent_category','slug'])->get();
        }
    }

    public function allCategoryList()
    {
        //$category = Category::with('children')->where('parent_category',AllStatic::$inactive)->get();
        $category = Category::with('children')->where('parent_category',AllStatic::$inactive)
                ->where('status',AllStatic::$active)->get();
        return response()->json($category);
    }
}
