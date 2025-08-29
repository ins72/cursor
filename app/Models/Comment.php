<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'parent_id',
        'content',
        'is_approved',
        'is_featured',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
        'is_featured' => 'boolean',
    ];

    /**
     * Get the user who made this comment
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the product this comment is on
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the parent comment (for replies)
     */
    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    /**
     * Get the replies to this comment
     */
    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    /**
     * Scope for approved comments
     */
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    /**
     * Scope for featured comments
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope for top-level comments (no parent)
     */
    public function scopeTopLevel($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Check if comment has replies
     */
    public function getHasRepliesAttribute()
    {
        return $this->replies()->count() > 0;
    }

    /**
     * Get formatted content (with line breaks)
     */
    public function getFormattedContentAttribute()
    {
        return nl2br(e($this->content));
    }
}