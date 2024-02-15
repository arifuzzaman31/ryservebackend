<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingConfig extends Model
{
    use HasFactory;
    protected $table = 'shipping_configs';
    protected $fillable = ['country_name','country_code','shipping_charge','status'];
}
