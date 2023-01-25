<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Manager extends Model
{
    protected $fillable = [
        'name',
        'type',
        'email'
    ];

    public function cities()
    {
        return $this->belongsToMany(
            City::class,
            'manager_city'
        );
    }
}
