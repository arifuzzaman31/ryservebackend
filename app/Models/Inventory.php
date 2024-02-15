<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inventory extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'product_id', 'colour_id', 'size_id', 'stock','cpu','mrp','sku', 'warning_amount', 'warehouse'];
    // protected $hidden = ['cpu'];
    protected $casts = [
        'id' => 'integer',
        'stock' => 'integer',
        'cpu' => 'float',
        'mrp' => 'float',
        'product_id' => 'integer',
        'colour_id' => 'integer',
        'size_id' => 'integer',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function colour()
    {
        return $this->belongsTo(Colour::class);
    }

    public function size()
    {
        return $this->belongsTo(Size::class);
    }

    public function order_details()
    {
        return $this->belongsTo(OrderDetails::class,'product_id');
    }
    public function discount()
    {
        return $this->hasOne(Discount::class,'disc_sku','sku');
    }
}
