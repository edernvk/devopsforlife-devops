<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Magazine extends Model
{

    protected $fillable = [
        'title',
        'cover', // nullable
        'link'
    ];

}
