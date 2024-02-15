<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class)->withDefault([
            'id'    =>  0,
            'name' => 'Unknown',
            'email' => 'Unknown',
            'phone' => 'Unknown',
            'address' => 'Unknown'
        ]);
    }

    public function delivery()
    {
        return $this->hasOne(Delivery::class)->withDefault([
            'id' => '0',
            'order_id' => ''
        ]);
    }

    public function user_billing_info()
    {
        return $this->hasOne(UserBillingInfo::class)->withDefault([
            'id' => 0,
            'first_name' => 'N/A',
            'country' => 'N/A',
            'email' => 'N/A',
            'city' => 'N/A',
            'phone' => 'N/A',
            'post_code' => 'N/A',
            'street_address' => 'N/A'
        ]);
    }

    public function user_shipping_info()
    {
        return $this->hasOne(UserShippingInfo::class)->withDefault([
            'id' => 0,
            'first_name' => 'N/A',
            'country' => 'N/A',
            'email' => 'N/A',
            'city' => 'N/A',
            'phone' => 'N/A',
            'post_code' => 'N/A',
            'street_address' => 'N/A'
        ]);
    }

    public function order_details()
    {
        return $this->hasMany(OrderDetails::class);
    }
}
