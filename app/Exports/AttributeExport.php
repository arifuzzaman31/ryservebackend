<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;


class AttributeExport implements FromArray, WithMultipleSheets
{
    protected $sheets;

    public function __construct(array $sheets)
    {
        $this->sheets = $sheets;
    }

    function array(): array
    {
        return $this->sheets;
    }

    public function sheets(): array
    {
        // ,
        // new BrandSheetExport($this->sheets['brand']),
        $cate = \DB::table('categories')->select('id', 'category_name', 'parent_category', 'status')->get()->toArray();
        $sheets = [new CategorySheetExport($cate)];
        foreach($this->sheets as $key => $value){
            $sheets[] = new BrandSheetExport($this->sheets[$key],$key);
        }

        return $sheets;
    }
}