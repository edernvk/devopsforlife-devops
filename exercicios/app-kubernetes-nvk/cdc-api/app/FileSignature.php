<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FileSignature extends Model
{
    protected $fillable = [
        'user_id',
        'file_id',
        'ip',
        'sing'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function file() {
        return $this->belongsTo(File::class);
    }
}
