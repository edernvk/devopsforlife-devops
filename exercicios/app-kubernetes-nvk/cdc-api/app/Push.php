<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Push extends Model
{

    protected $fillable = [
        'title',
        'message',
        'type',
        'delivered',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
