<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddressBook extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','first_name','last_name','country','city','email','phone','post_code','apartment','street_address'];
    protected $cast = [
        'id'   => 'integer',
        'user_id'   => 'integer'
    ];
}
