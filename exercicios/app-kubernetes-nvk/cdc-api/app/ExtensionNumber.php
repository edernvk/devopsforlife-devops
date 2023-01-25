<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExtensionNumber extends Model
{
    protected $fillable = [
        'name',
        'number',
        'parentable_id',
        'parentable_type',
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function parentable() {
        return $this->morphTo();
    }

//    public function division() {
//        return $this->belongsTo(ExtensionDivision::class, 'division_id');
//    }
//
//    public function area() {
//        return $this->belongsTo(ExtensionArea::class, 'area_id');
//    }


//    protected static function boot() {
//        parent::boot();
//
//        static::addGlobalScope('division', function($builder) {
//            $builder->with('division');
//        });
//
//        static::addGlobalScope('area', function($builder) {
//            $builder->with('area');
//        });
//    }
}
