<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserIosCodeTracked extends Model
{
    protected $fillable = [
        'user_id', 'ioscode_id'
    ];
}
