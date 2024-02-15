<?php

namespace App\Exports;

use App\Models\Inventory;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ProductExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        $product = Inventory::with('product.category:id,category_name','product.subcategory:id,category_name',
        'product.product_brand:id,brand_name','product.product_fabric:id,fabric_name','colour:id,color_name',
        'product.product_size:id,size_name','product.product_designer:id,designer_name','product.product_embellishment:id,embellishment_name',
        'product.product_making:id,making_name','product.product_season:id,season_name','product.product_variety:id,variety_name',
        'product.product_fit:id,fit_name','product.product_artist:id,artist_name','product.product_consignment:id,consignment_name',
        'product.product_ingredient:id,ingredient_name')->get();

        return view('pages.product.excel.download_product',['products' => $product]);
    }
}
