<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserVideocastTracked extends Model
{
    protected $table = 'user_videocast_tracked';

    public $timestamps = false;

    protected $fillable = [
        'user_id', 'videocast_id', 'participation'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function videocast()
    {
        return $this->belongsTo(Videocast::class);
    }
}
