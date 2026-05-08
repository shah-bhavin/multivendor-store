<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes; // Use the trait
    protected $fillable = [

        'name',
        'category_id',
        'description',
        'price',
        'stock',
        'image',
        'status',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
