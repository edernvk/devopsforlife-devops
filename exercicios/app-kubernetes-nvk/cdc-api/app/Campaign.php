<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'description',
        'entry_date',
        'departure_date'
    ];
}
