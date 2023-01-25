<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StatusMessage extends Model
{
    use SoftDeletes;

    const INACTIVE = 1;
    const DRAFT = 2;
    const PUBLISHED = 3;
    const SCHEDULED = 4;

    protected $table = 'status_message';

    protected $guarded = [];

    public $timestamps = false;

    public function messages() {
        return $this->hasMany(Message::class);
    }
}
