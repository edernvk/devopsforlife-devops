<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Benefit extends Model
{
    protected $fillable = [
        'partner',
        'contact',
        'benefit',
        'parentable_id',
        'parentable_type'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function parentable() {
        return $this->morphTo();
    }

//    public function division() {
//        return $this->belongsTo(BenefitDivision::class, 'benefit_division_id');
//    }
//
//    public function area() {
//        return $this->belongsTo(BenefitArea::class, 'benefit_area_id');
//    }


//    protected static function boot() {
//        parent::boot();
//
//        // return eager loaded relationships
//        static::addGlobalScope('division', function($builder) {
//            $builder->with('division');
//        });
//        static::addGlobalScope('area', function($builder) {
//            $builder->with('area');
//        });
//    }
}
