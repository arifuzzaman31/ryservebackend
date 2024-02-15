<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Discount extends Model
{
    use HasFactory;
    protected $table = 'discounts';
    protected $fillable = ['product_id','discount_amount','discount_type','type','max_amount','disc_sku','status'];
    protected $casts = [
        'id'   => 'integer',
        'product_id'   => 'integer',
        'discount_amount'   => 'float',
        'max_amount'   => 'float'
    ];
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }
}

