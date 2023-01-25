<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExtensionArea extends Model
{
    protected $fillable = [
        'name',
        'extension_division_id'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function division() {
        return $this->belongsTo(ExtensionDivision::class, 'extension_division_id');
    }

    public function numbers() {
        return $this->morphMany(ExtensionNumber::class, 'parentable');
    }

}
