<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = ['id','section_name','banner','pattern','product_id','use_for','precedence','status'];
    // protected $casts = [
    //     'product_id' => 'array'
    // ];
}
