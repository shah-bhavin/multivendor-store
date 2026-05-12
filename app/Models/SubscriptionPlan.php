<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan
    extends Model
{
    protected $fillable = [

        'name',

        'slug',

        'description',

        'price',

        'billing_cycle',

        'product_limit',

        'featured_products',

        'priority_support',

        'analytics_access',

        'is_active',
    ];
}