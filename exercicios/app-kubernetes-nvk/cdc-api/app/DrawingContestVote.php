<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DrawingContestVote extends Model
{
    protected $fillable = [
        'category_id',
        'picture_id',
        'user_id',
        'campaign_stage'
    ];

    public function category()
    {
        return $this->hasOne(DrawingContestCategory::class, 'id', 'category_id');
    }

    public function picture()
    {
        return $this->hasOne(DrawingContestPicture::class, 'id', 'picture_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
