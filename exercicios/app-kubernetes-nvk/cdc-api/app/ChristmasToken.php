<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChristmasToken extends Model
{
    protected $table = 'christmas_tokens';

    protected $guarded = [];

    public $timestamps = false;

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
}
