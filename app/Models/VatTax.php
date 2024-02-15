<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VatTax extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = ['id', 'tax_name', 'tax_percentage', 'status'];
    protected $casts = [
        'tax_percentage' => 'float'
    ];

    public function getFullvatAttribute()
    {
        return "{$this->tax_name} - {$this->tax_percentage}%";
    }

    public function product()
    {
        return $this->hasOne(Product::class);
    }
}
