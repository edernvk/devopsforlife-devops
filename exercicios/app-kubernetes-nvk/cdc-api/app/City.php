<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $guarded = [];

    protected $with = [
        'state'
    ];

    public $timestamps = false;

    public function users() {
        return $this->hasMany(User::class);
    }

    public function managers() {
        return $this->belongsToMany(Manager::class, 'manager_city');
    }

    public function state() {
        return $this->belongsTo(State::class);
    }
}
