<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaManager extends Model
{
    use HasFactory;
    protected $fillable = ['file_link','file_type','product_name','extension','cld_public_id','status'];
}
