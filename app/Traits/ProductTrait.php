<?php

namespace App\Traits;
use App\Http\AllStatic;
use Illuminate\Support\Facades\DB;

trait ProductTrait {

   public function getArtist()
   {
        $artist = DB::table('artists')->selectRaw('id as value,artist_name as name,status')->where('status',AllStatic::$active)->whereNull('deleted_at')
                ->orderBy('id','desc')->get();
        return $artist;
   }

    public function getVendor()
    {
        $vendor = DB::table('vendors')->selectRaw('id as value,vendor_name as name,status')->where('status',AllStatic::$active)->whereNull('deleted_at')
                ->orderBy('id','desc')->get();
        return $vendor;
    }

    public function getBrand()
    {
        $brand = DB::table('brands')->selectRaw('id as value,brand_name as name,status')->where('status',AllStatic::$active)->whereNull('deleted_at')
                ->orderBy('id','desc')->get();
        return $brand;
    }
    public function getCare()
    {
        $care =  DB::table('cares')->selectRaw('id as value,care_name as name,status')->where('status',AllStatic::$active)->whereNull('deleted_at')
                ->orderBy('id','desc')->get();
        return $care;
    }
    public function getConsignment()
    {
        $cansign =  DB::table('consignments')->selectRaw('id as value,consignment_name as name,status')->where('status',AllStatic::$active)->whereNull('deleted_at')
                ->orderBy('id','desc')->get();
        return $cansign;
    }
    public function getDesigner()
    {
        $designer = DB::table('designers')->selectRaw('id as value,designer_name as name,status')->where('status',AllStatic::$active)->whereNull('deleted_at')->orderBy('id','desc')->get();
        return $designer;
    }
    public function getEmbellishment()
    {
        $embel = DB::table('embellishments')->selectRaw('id as value,embellishment_name as name,status')->where('status',AllStatic::$active)->whereNull('deleted_at')->orderBy('id','desc')->get();
        return $embel;
    }
    public function getFabric()
    {
        $fabric = DB::table('fabrics')->selectRaw('id as value,fabric_name as name,status')->where('status',AllStatic::$active)->whereNull('deleted_at')->orderBy('id','desc')->get();
        return $fabric;
    }
    public function getFit()
    {
        $fit = DB::table('fits')->selectRaw('id as value,fit_name as name,status')->where('status',AllStatic::$active)->whereNull('deleted_at')->orderBy('id','desc')->get();
        return $fit;
    }
    public function getIngredient()
    {
        $ingredient = DB::table('ingredients')->selectRaw('id as value,ingredient_name as name,status')->where('status',AllStatic::$active)->whereNull('deleted_at')->orderBy('id','desc')->get();
        return $ingredient;
    }
    public function getMaking()
    {
        $making = DB::table('makings')->selectRaw('id as value,making_name as name,status')->where('status',AllStatic::$active)->whereNull('deleted_at')->orderBy('id','desc')->get();
        return $making;
    }
    public function getSeason()
    {
        $season = DB::table('seasons')->selectRaw('id as value,season_name as name,status')->where('status',AllStatic::$active)->whereNull('deleted_at')->orderBy('id','desc')->get();
        return $season;
    }
    public function getSize()
    {
        $size = DB::table('sizes')->selectRaw('id as value,size_name as name,status')->where('status',AllStatic::$active)->whereNull('deleted_at')->orderBy('id','desc')->get();
        return $size;
    }

    public function getColour()
    {
        $colour = DB::table('colours')->selectRaw('id as value,color_name as name,status')->where('status',AllStatic::$active)->whereNull('deleted_at')->orderBy('id','desc')->get();
        return $colour;
    }

    public function flatColour()
    {
        $colour = DB::table('colours')->selectRaw('color_name as value,color_name as name,status')->where('status',AllStatic::$active)->whereNull('deleted_at')->orderBy('id','desc')->get();
        return $colour;
    }
    public function getVariety()
    {
        $variety = DB::table('varieties')->selectRaw('id as value,variety_name as name,status')->where('status',AllStatic::$active)->whereNull('deleted_at')->orderBy('id','desc')->get();
        return $variety;
    }
    public function getTax()
    {
        $tax = DB::table('vat_taxes')->select('id as value',DB::Raw("CONCAT(tax_name, '-', tax_percentage,'%') AS name"),'status')->whereNull('deleted_at')->where('status',AllStatic::$active)->orderBy('id','desc')->get();
        return $tax;
    }

}