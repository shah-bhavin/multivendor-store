<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [

        'customer_name',
        'customer_email',
        'customer_phone',
        'customer_address',
        'total_amount',
        'status',
        'user_id',

        'payment_id',
        'payment_status',

        'shipping_name',
        'shipping_phone',
        'shipping_address',
        'shipping_city',
        'shipping_state',
        'shipping_country',
        'shipping_pincode',
        'shipping_charge',
        'shipping_method',
        'delivery_status',
        'shipped_at',
        'delivered_at',
        'tracking_number',
    ];

    public function items()
    {
        return $this->hasMany(
            OrderItem::class
        );
    }

    public function user()
    {
        return $this->belongsTo(
            User::class
        );
    }

    public function isDelivered()
    {
        return $this->delivery_status
            === 'Delivered';
    }

    public function isShipped()
    {
        return $this->delivery_status
            === 'Shipped';
    }

    public function returnRequests()
    {
        return $this->hasMany(
            ReturnRequest::class
        );
    }
}
