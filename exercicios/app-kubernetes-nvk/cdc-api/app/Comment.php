<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{

    protected $table = 'comments';

    protected $fillable = [
        'content',
        'status',
        'user_id',
        'newsletter_news_id'
    ];

    public function commentUser()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function commentNewsletter()
    {
        return $this->belongsTo(NewsletterNews::class, 'newsletter_news_id');
    }

}
