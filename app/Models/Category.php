<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory,SoftDeletes;

    public function product()
    {
        return $this->hasOne(Product::class);
    }
    public function order_details()
    {
        return $this->hasOne(OrderDetails::class);
    }

    public function subcategory()
    {
        return $this->belongsTo(Category::class, 'parent_category')->withDefault([
            'id' => 0,
            'category_name'  => 'N/A'
        ]);
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_category')->where('status',1);
    }

    public function composition()
    {
        return $this->belongsToMany(Fabric::class,'category_fabric')->withTimestamps();
    }

}
