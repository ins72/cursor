<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'rating',
        'review',
        'is_verified',
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_verified' => 'boolean',
    ];

    /**
     * Get the user who gave this rating
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the product being rated
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Scope for verified ratings
     */
    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    /**
     * Scope for ratings by rating value
     */
    public function scopeByRating($query, $rating)
    {
        return $query->where('rating', $rating);
    }

    /**
     * Get formatted rating
     */
    public function getFormattedRatingAttribute()
    {
        return number_format($this->rating, 1);
    }

    /**
     * Get rating stars HTML
     */
    public function getStarsHtmlAttribute()
    {
        $stars = '';
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $this->rating) {
                $stars .= '<svg class="icon icon-star-fill"><use xlink:href="#icon-star-fill"></use></svg>';
            } else {
                $stars .= '<svg class="icon icon-star-stroke"><use xlink:href="#icon-star-stroke"></use></svg>';
            }
        }
        return $stars;
    }
}