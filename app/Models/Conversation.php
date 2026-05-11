<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
     protected $fillable = [

        'customer_id',

        'vendor_id',

        'product_id',
    ];

    public function customer()
    {
        return $this->belongsTo(

            User::class,

            'customer_id'
        );
    }

    public function vendor()
    {
        return $this->belongsTo(

            User::class,

            'vendor_id'
        );
    }

    public function product()
    {
        return $this->belongsTo(
            Product::class
        );
    }

    public function messages()
    {
        return $this->hasMany(
            Message::class
        );
    }
}
