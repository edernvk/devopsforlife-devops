<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BenefitArea extends Model
{
    protected $fillable = [
        'name',
        'benefit_division_id'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function division() {
        return $this->belongsTo(BenefitDivision::class, 'benefit_division_id');
    }

    public function benefits() {
        return $this->morphMany(Benefit::class, 'parentable');
    }
}
