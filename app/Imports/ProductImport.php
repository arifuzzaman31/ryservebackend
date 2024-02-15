<?php

namespace App\Imports;

use App\Models\Inventory;
use App\Models\Product;
use App\Models\ProductTag;
use App\Models\CategoryFabric;
use App\Models\ProductSize;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Str;

class ProductImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        unset($rows[0]);
        // return count($rows);
        $data = array_filter($rows->toArray(),function ($number) {
                return $number[0] !== null;
            });
            //  dd($data[2]);
        foreach($data as $row)
        {
            $ch = Product::where('design_code',$row[1])->first();
                if($ch){
                    $this->putCombAttr($row,$ch);
                    if($row[12] != ''){
                        $chksiz = ProductSize::where(
                            ['product_id' =>  $ch->id,'size_id' => $row[12]]
                            )->first();

                        if(empty($chksiz)){
                            ProductSize::create(
                                ['product_id' =>  $ch->id,'size_id' => $row[12]]
                            );
                        }
                    }

                } else {

                if($row[25] != ''){
                    try {
                        DB::beginTransaction();

                        $product = Product::create([
                            'product_name' => $row[2],
                            'slug' => Str::slug($row[2]),
                            'category_id' => (int) $row[5],
                            'sub_category_id' => (int)$row[6],
                            'vat_tax_id' => $row[35], //not found
                            'lead_time' => $row[15],
                            'product_image' => $row[25] !='' ? $row[25] : '',
                            'image_one' => $row[25] !='' ? $row[25] : '',
                            'image_two' => $row[26] !='' ? $row[26] : '',
                            'image_three' => $row[27] !='' ? $row[27] : '',
                            'image_four' => $row[28] !='' ? $row[28] : '',
                            'image_five' => $row[29] !='' ? $row[29] : '',
                            'height' => $row[30] != '' ? explode(",",$row[30])[0] : NULL,
                            'width' => $row[30] != '' ? explode(",",$row[30])[1] : NULL,
                            'length' => $row[30] != '' ? explode(",",$row[30])[2] : NULL,
                            'unit' => $row[31] != '' ? $row[31] : NULL,
                            // 'country_of_origin' => $row[0],
                            'weight' => $row[32],
                            'design_code' => $row[1],
                            'description' => $row[3],
                            'status' => 1,
                            'is_discount' => 0,
                            'fragile' => 0,
                            'fragile_charge' => 0,
                            'vat_tax_id' => $row[35],
                            'has_variation' => $row[8] == '1' ? 1 : 0,
                            'flat_colour' => $row[36] != '' ? $row[36] : NULL,
                            'fragile' => $row[37] != '' ? 1 : 0
                        ]);

                        $this->putCombAttr($row,$product);
                        if(!empty($row[19])){
                            $pc = explode(",",$row[19]);
                            $product->product_colour()->attach($pc);
                        }

                        if(!empty($row[12])){
                            $ps = explode(",",$row[12]);
                            $product->product_size()->attach($ps);
                        }

                        if(!empty($row[11])){
                            $pb = explode(",",$row[11]);
                            $product->product_fabric()->attach($pb);
                        }
                        if(!empty($row[4])){
                            $pv = explode(",",$row[4]);
                            $product->product_vendor()->attach($pv);
                        }
                        if(!empty($row[7])){
                            $pbr = explode(",",$row[7]);
                            $product->product_brand()->attach($pbr);
                        }
                        if(!empty($row[9])){
                            $pd = explode(",",$row[9]);
                            $product->product_designer()->attach($pd);
                        }
                        if(!empty($row[10])){
                            $pem = explode(",",$row[10]);
                            $product->product_embellishment()->attach($pem);
                        }
                        if(!empty($row[14])){
                            $pmk = explode(",",$row[14]);
                            $product->product_making()->attach($pmk);
                        }
                        if(!empty($row[16])){
                            $pse = explode(",",$row[16]);
                            $product->product_season()->attach($pse);
                        }
                        if(!empty($row[17])){
                            $pva = explode(",",$row[17]);
                            $product->product_variety()->attach($pva);
                        }
                        if(!empty($row[18])){
                            $pf = explode(",",$row[18]);
                            $product->product_fit()->attach($pf);
                        }
                        if(!empty($row[20])){
                            $par = explode(",",$row[20]);
                            $product->product_artist()->attach($par);
                        }
                        if(!empty($row[21])){
                            $pco = explode(",",$row[21]);
                            $product->product_consignment()->attach($pco);
                        }
                        if(!empty($row[13])){
                            $pin = explode(",",$row[13]);
                            $product->product_ingredient()->attach($pin);
                        }
                        if(!empty($row[33])){
                            $pca = explode(",",$row[33]);
                            $product->product_care()->attach($pca);
                        }

                        if(!empty($row[34])){

                            ProductTag::create([
                                'product_id' => $product->id,
                                'keyword_name' => $row[34]
                            ]);
                        }

                        DB::commit();
                    } catch (\Throwable $th) {
                        //throw $th;
                        DB::rollBack();
                        return $th;

                    }
                }
            }
         // dd($row[2]);


        }
    }

    public function putCombAttr($row,$ch)
    {
        // foreach([25,26,27,28,29] as $value)
        // {
        //     if($row[$value] !=''){
        //         DB::table('media_managers')->insert([
        //             'file_link' => $row[$value],
        //             'file_type' => 'image',
        //             'product_name' =>  explode('.',$row[$value])[0],
        //             'cld_public_id' =>  $value['public_id'],
        //             'extension' => explode('.',$row[$value])[1],
        //             'created_at' => now()
        //         ]);
        //     }
        // }
      $pc = explode(",",$row[19]);
      if(!empty($pc)){
         foreach($pc as $vl)
         {
            Inventory::create([
               'product_id'  => $ch->id,
               'colour_id'   => $vl ? $vl : 0,
               'size_id'     => $row[12] != '' ? $row[12] : 0,
               'sku'         => $row[0],
               'cpu'         => (float)$row[22],
               'mrp'         => (float)$row[23],
               'stock'       => (int)$row[24]
            ]);
         }

      } else {
         Inventory::create([
            'product_id'  => $ch->id,
            'colour_id'   => 0,
            'size_id'     => $row[12] != '' ? $row[12] : 0,
            'sku'         => $row[0],
            'cpu'         => (float)$row[22],
            'mrp'         => (float)$row[23],
            'stock'       => (int)$row[24]
         ]);

      }
    }
}
