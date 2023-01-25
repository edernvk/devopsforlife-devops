<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DrawingContestPicture extends Model
{
    use \Staudenmeir\EloquentEagerLimit\HasEagerLimit;

    protected $fillable = [
        'category_id',
        'url',
        'subscription'
    ];

    public function category()
    {
        return $this->belongsTo(DrawingContestCategory::class, 'category_id', 'id');
    }

    public function votes()
    {
        return $this->hasMany(DrawingContestVote::class, 'picture_id');
    }
}
