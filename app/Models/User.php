<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
        'avatar',
        'bio',
        'social_links',
        'is_verified',
        'is_featured',
        'subscription_status',
        'subscription_ends_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'social_links' => 'array',
        'is_verified' => 'boolean',
        'is_featured' => 'boolean',
        'subscription_ends_at' => 'datetime',
    ];

    /**
     * Get the products created by this user
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Get the ratings given by this user
     */
    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    /**
     * Get the purchases made by this user
     */
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    /**
     * Get the wishlist items for this user
     */
    public function wishlist()
    {
        return $this->hasMany(Wishlist::class);
    }

    /**
     * Get the followers of this user
     */
    public function followers()
    {
        return $this->hasMany(Follow::class, 'following_id');
    }

    /**
     * Get the users this user is following
     */
    public function following()
    {
        return $this->hasMany(Follow::class, 'follower_id');
    }

    /**
     * Get the notifications for this user
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Get the messages sent by this user
     */
    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    /**
     * Get the messages received by this user
     */
    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'recipient_id');
    }

    /**
     * Check if this user is following another user
     */
    public function isFollowing($userId)
    {
        return $this->following()->where('following_id', $userId)->exists();
    }

    /**
     * Check if this user has purchased a specific product
     */
    public function hasPurchased($productId)
    {
        return $this->purchases()->where('product_id', $productId)->exists();
    }

    /**
     * Check if this user has rated a specific product
     */
    public function hasRated($productId)
    {
        return $this->ratings()->where('product_id', $productId)->exists();
    }

    /**
     * Check if this user has a product in their wishlist
     */
    public function hasInWishlist($productId)
    {
        return $this->wishlist()->where('product_id', $productId)->exists();
    }

    /**
     * Get the total earnings for this user
     */
    public function getTotalEarnings()
    {
        return $this->products()->withSum('purchases', 'amount')->get()->sum('purchases_sum_amount');
    }

    /**
     * Get the total products count for this user
     */
    public function getProductsCount()
    {
        return $this->products()->count();
    }

    /**
     * Get the total followers count for this user
     */
    public function getFollowersCount()
    {
        return $this->followers()->count();
    }

    /**
     * Get the total following count for this user
     */
    public function getFollowingCount()
    {
        return $this->following()->count();
    }

    /**
     * Check if user has pro subscription
     */
    public function isPro()
    {
        return $this->subscription_status === 'pro' && 
               ($this->subscription_ends_at === null || $this->subscription_ends_at->isFuture());
    }

    /**
     * Get avatar URL with fallback
     */
    public function getAvatarUrlAttribute()
    {
        return $this->avatar ?? asset('frontend/build/img/content/avatar.jpg');
    }

    /**
     * Get social links with defaults
     */
    public function getSocialLinksAttribute($value)
    {
        $defaults = [
            'twitter' => null,
            'instagram' => null,
            'pinterest' => null,
            'website' => null,
        ];

        return array_merge($defaults, json_decode($value, true) ?? []);
    }
}