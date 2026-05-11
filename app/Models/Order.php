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
}
