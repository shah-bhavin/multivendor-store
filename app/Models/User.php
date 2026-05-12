<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [

        'name',
        'email',
        'password',
        'store_name',
        'store_description',
        'store_slug',
        'store_logo',
        'store_banner',
    ];

    protected $hidden = [

        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function canAccessPanel(\Filament\Panel $panel): bool
    {
        return $this->hasRole('admin');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
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

    public function customerConversations()
    {
        return $this->hasMany(

            Conversation::class,

            'customer_id'
        );
    }

    public function vendorConversations()
    {
        return $this->hasMany(

            Conversation::class,

            'vendor_id'
        );
    }

    public function messages()
    {
        return $this->hasMany(

            Message::class,

            'sender_id'
        );
    }

    public function isVendor()
    {
        return $this->hasRole(
            'vendor'
        );
    }

    public function subscriptions()
    {
        return $this->hasMany(
            VendorSubscription::class
        );
    }

    public function activeSubscription()
    {
        return $this->hasOne(
            VendorSubscription::class
        )

        ->where('status', 'active')

        ->where('expires_at', '>', now());
    }
}
