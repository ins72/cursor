<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'price',
        'category',
        'tags',
        'image',
        'image_2x',
        'status',
        'is_featured',
        'featured_at',
        'download_count',
        'view_count',
        'purchase_count',
        'rating',
        'rating_count',
        'release_date',
        'file_size',
        'file_type',
        'demo_url',
        'documentation_url',
        'support_email',
        'license_type',
        'compatibility',
        'version',
        'changelog',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'tags' => 'array',
        'is_featured' => 'boolean',
        'featured_at' => 'datetime',
        'rating' => 'decimal:2',
        'release_date' => 'datetime',
        'compatibility' => 'array',
    ];

    /**
     * Get the user who created this product
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the ratings for this product
     */
    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    /**
     * Get the purchases for this product
     */
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    /**
     * Get the comments for this product
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get the wishlist entries for this product
     */
    public function wishlist()
    {
        return $this->hasMany(Wishlist::class);
    }

    /**
     * Get the notifications related to this product
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Scope for published products
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope for featured products
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope for products by category
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope for products by price range
     */
    public function scopeByPriceRange($query, $min, $max)
    {
        return $query->whereBetween('price', [$min, $max]);
    }

    /**
     * Scope for products by rating
     */
    public function scopeByRating($query, $minRating)
    {
        return $query->where('rating', '>=', $minRating);
    }

    /**
     * Scope for search
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%")
              ->orWhere('tags', 'like', "%{$search}%");
        });
    }

    /**
     * Get the average rating for this product
     */
    public function getAverageRating()
    {
        return $this->ratings()->avg('rating') ?? 0;
    }

    /**
     * Get the total rating count for this product
     */
    public function getRatingCount()
    {
        return $this->ratings()->count();
    }

    /**
     * Update product rating statistics
     */
    public function updateRatingStats()
    {
        $this->rating = $this->getAverageRating();
        $this->rating_count = $this->getRatingCount();
        $this->save();
    }

    /**
     * Increment view count
     */
    public function incrementViewCount()
    {
        $this->increment('view_count');
    }

    /**
     * Increment download count
     */
    public function incrementDownloadCount()
    {
        $this->increment('download_count');
    }

    /**
     * Get formatted price
     */
    public function getFormattedPriceAttribute()
    {
        if ($this->price == 0) {
            return 'Free';
        }
        return '$' . number_format($this->price, 2);
    }

    /**
     * Check if product is free
     */
    public function getIsFreeAttribute()
    {
        return $this->price == 0;
    }

    /**
     * Get product status badge
     */
    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'published' => 'success',
            'draft' => 'warning',
            'scheduled' => 'info',
            'archived' => 'secondary',
            default => 'secondary'
        };
    }

    /**
     * Get category badge
     */
    public function getCategoryBadgeAttribute()
    {
        return match($this->category) {
            'ui-kit' => 'primary',
            'illustration' => 'success',
            'wireframe' => 'info',
            'icons' => 'warning',
            default => 'secondary'
        };
    }

    /**
     * Get image URL with fallback
     */
    public function getImageUrlAttribute()
    {
        return $this->image ?? asset('frontend/build/img/content/product-pic-1.jpg');
    }

    /**
     * Get 2x image URL with fallback
     */
    public function getImage2xUrlAttribute()
    {
        return $this->image_2x ?? $this->image ?? asset('frontend/build/img/content/product-pic-1@2x.jpg');
    }

    /**
     * Get product URL
     */
    public function getUrlAttribute()
    {
        return route('products.show', $this);
    }

    /**
     * Get edit URL
     */
    public function getEditUrlAttribute()
    {
        return route('products.edit', $this);
    }

    /**
     * Get delete URL
     */
    public function getDeleteUrlAttribute()
    {
        return route('products.destroy', $this);
    }

    /**
     * Get purchase URL
     */
    public function getPurchaseUrlAttribute()
    {
        return route('products.purchase', $this);
    }

    /**
     * Get rating URL
     */
    public function getRatingUrlAttribute()
    {
        return route('products.rate', $this);
    }

    /**
     * Get wishlist URL
     */
    public function getWishlistUrlAttribute()
    {
        return route('products.wishlist', $this);
    }

    /**
     * Boot method to handle model events
     */
    protected static function boot()
    {
        parent::boot();

        // Update rating stats when rating is created/updated/deleted
        static::updated(function ($product) {
            if ($product->wasChanged('rating') || $product->wasChanged('rating_count')) {
                $product->updateRatingStats();
            }
        });
    }
}