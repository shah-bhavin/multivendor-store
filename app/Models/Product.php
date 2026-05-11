<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model implements HasMedia
{
    use InteractsWithMedia;
    use SoftDeletes; // Use the trait
    protected $fillable = [

        'name',
        'category_id',
        'user_id',
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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function wishlists()
    {
        return $this->hasMany(
            Wishlist::class
        );
    }

    public function reviews()
    {
        return $this->hasMany(
            Review::class
        );
    }

    public function averageRating()
    {
        return round(

            $this->reviews()
                ->avg('rating'),

            1
        );
    }

    public function conversations()
    {
        return $this->hasMany(
            Conversation::class
        );
    }
}
