<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'amount',
        'status',
        'payment_method',
        'payment_id',
        'transaction_id',
        'purchased_at',
        'download_count',
        'last_download_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'purchased_at' => 'datetime',
        'last_download_at' => 'datetime',
    ];

    /**
     * Get the user who made this purchase
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the product that was purchased
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Scope for completed purchases
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope for pending purchases
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for failed purchases
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Scope for refunded purchases
     */
    public function scopeRefunded($query)
    {
        return $query->where('status', 'refunded');
    }

    /**
     * Get formatted amount
     */
    public function getFormattedAmountAttribute()
    {
        return '$' . number_format($this->amount, 2);
    }

    /**
     * Get status badge
     */
    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'completed' => 'success',
            'pending' => 'warning',
            'failed' => 'danger',
            'refunded' => 'secondary',
            default => 'secondary'
        };
    }

    /**
     * Check if purchase is downloadable
     */
    public function getIsDownloadableAttribute()
    {
        return $this->status === 'completed';
    }

    /**
     * Increment download count
     */
    public function incrementDownloadCount()
    {
        $this->increment('download_count');
        $this->update(['last_download_at' => now()]);
    }
}