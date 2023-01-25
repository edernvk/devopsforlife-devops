<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HealthDocs extends Model
{
    protected $guarded = [];

    protected $table = 'healthdocs';

    public function user() {
        return $this->belongsTo(User::class);
    }
}
