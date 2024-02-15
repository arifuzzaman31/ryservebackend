<?php

namespace App\Exports;

use App\Models\Product;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class AddProduct implements FromView
{
   /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        $product = Product::with(['category:id,category_name,slug',
        'subcategory:id,category_name,slug','product_brand:id,brand_name','inventory.discount',
        'inventory.colour','inventory.size','campaign'
        ])
        ->get();
        // return $product;
        return view('pages.product.excel.product',['products' => $product]);
    }
}
