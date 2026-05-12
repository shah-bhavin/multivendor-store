<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

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
        'low_stock_alert',
        'track_inventory',
        'in_stock',
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

    public function isOutOfStock()
    {
        if (!$this->track_inventory) {

            return false;
        }

        return $this->stock <= 0;
    }

    public function isLowStock()
    {
        if (! $this->track_inventory) {

            return false;
        }

        return $this->stock <=
            $this->low_stock_alert;
    }

    public function reduceStock($quantity)
    {
        if (! $this->track_inventory) {

            return;
        }

        $this->decrement(
            'stock',

            $quantity
        );

        /*
    |--------------------------------------------------------------------------
    | Update Stock Status
    |--------------------------------------------------------------------------
    */

        if ($this->fresh()->stock <= 0) {

            $this->update([

                'in_stock' => false
            ]);
        }
    }

    protected static function booted()
    {
        static::creating(function ($product) {

            $product->slug = Str::slug(

                $product->name
            );
        });
    }
}
