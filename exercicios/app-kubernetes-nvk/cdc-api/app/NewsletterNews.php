<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class NewsletterNews extends Model
{
    protected $fillable = [
        'title',
        'content',
        'user_id',
        'is_active',
        'status_id',
        'publish_datetime',
        'desactivated_at',
        'contrast',
        'thumbnail', //nullable
        'commentable'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'contrast' => 'boolean',
    ];

    public function changeStatus()
    {
        return $this->update([
            'is_active' => !$this->attributes['is_active'],
            'desactivated_at' => $this->attributes['is_active'] === 0 ? now() : null
        ]);
    }

    public function markContrast()
    {
        return $this->update([
            'contrast' => true
        ]);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'newsletter_news_user')
        ->withPivot('read');
    }

    public function usersLike(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'newsletter_like')
        ->withPivot('like');
    }

    public function newslettersNewsComment()
    {
        return $this->hasMany(Comment::class, 'comments');
    }

}
