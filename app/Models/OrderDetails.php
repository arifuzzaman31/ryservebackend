<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderDetails extends Model
{
    use HasFactory;

    public function getTotalAttribute()
    {
        return $this->options->sum(function ($option) {
            return $option->total_selling_price;
        });
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class)->withDefault([
            'id'    => 0,
            'product_name' => 'N/A',
            'slug' => 'N/A'

        ]);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function colour()
    {
        return $this->belongsTo(Colour::class)->withDefault([
            'id'    => 0,
            'color_name' => 'N/A',
            'color_code' => 'N/A',
            'slug' => 'N/A'

        ]);
    }

    public function size()
    {
        return $this->belongsTo(Size::class)->withDefault([
            'id'    => 0,
            'size_name' => 'N/A',
            'slug' => 'N/A'

        ]);
    }

    public function fabric()
    {
        return $this->belongsTo(Fabric::class)->withDefault([
            'id'    => 0,
            'fabric_name' => 'N/A',
            'fabric_code' => 'N/A',
            'slug' => 'N/A'

        ]);
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withDefault([
            'id'    => 0,
            'first_name' => 'Unknown',
            'last_name' => 'Unknown',
            'name' => 'Unknown'

        ]);
    }

    public function inventory()
    {
        return $this->hasMany(Inventory::class,'product_id');
    }
}
