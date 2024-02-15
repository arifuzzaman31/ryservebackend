<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryFabric extends Model
{
    use HasFactory;

    protected $table="category_fabric";
    protected $fillable = ["category_id","fabric_id"];
    protected $cast = [
        'id'   => 'integer',
        'fabric_id'   => 'integer',
        'category_id'   => 'integer'
    ];

    // public function category()
    // {
    //     return $this->belongsTo(Category::class)->withDefault([
    //         'id'                   =>  0,
    //         'cat_name'        =>  'unknown',
    //         'category_name'        =>  'unknown',
    //         'slug'                 =>  'unknown'
    //       ]);
    // }

    // public function product_fabric()
    // {
    //     return $this->belongsToMany(Fabric::class,'product_fabrics')->withTimestamps();
    // }
}
