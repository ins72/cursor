<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'recipient_id',
        'subject',
        'content',
        'is_read',
        'read_at',
        'is_deleted_by_sender',
        'is_deleted_by_recipient',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'is_deleted_by_sender' => 'boolean',
        'is_deleted_by_recipient' => 'boolean',
    ];

    /**
     * Get the user who sent this message
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Get the user who received this message
     */
    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    /**
     * Scope for unread messages
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope for read messages
     */
    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    /**
     * Scope for recent messages
     */
    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Mark message as read
     */
    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }

    /**
     * Get formatted content (with line breaks)
     */
    public function getFormattedContentAttribute()
    {
        return nl2br(e($this->content));
    }

    /**
     * Get truncated content for preview
     */
    public function getPreviewAttribute()
    {
        return \Str::limit(strip_tags($this->content), 100);
    }

    /**
     * Check if message is from current user
     */
    public function isFromCurrentUser()
    {
        return $this->sender_id === auth()->id();
    }

    /**
     * Check if message is to current user
     */
    public function isToCurrentUser()
    {
        return $this->recipient_id === auth()->id();
    }
}