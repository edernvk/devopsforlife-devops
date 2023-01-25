<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IosCode extends Model
{
    protected $fillable = [
        'code', 'link', 'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];
}
