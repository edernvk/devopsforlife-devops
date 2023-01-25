<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{

    protected $table = 'groups';

    protected $fillable = [
        'name',
    ];

    public function userGroup ()
    {
        return $this->belongsToMany(User::class, 'user_group', 'group_id', 'user_id');
    }
}
