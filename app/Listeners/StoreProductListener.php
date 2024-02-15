<?php

namespace App\Listeners;

use App\Events\ProductProcessed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Excel;
use App\Imports\ProductImport;
use Illuminate\Support\Facades\Log;

class StoreProductListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\ProductProcessed  $event
     * @return void
     */
    public function handle(ProductProcessed $event)
    {
        $path = public_path('excel/import');
        $files = glob("$path/product.xlsx");
        $data = Excel::toArray($files, function($reader) {})->get();
      
        Excel::import(new ProductImport, $data);
    }
}
