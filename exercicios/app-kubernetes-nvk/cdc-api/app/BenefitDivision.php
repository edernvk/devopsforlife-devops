<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BenefitDivision extends Model
{
    use \Staudenmeir\EloquentHasManyDeep\HasRelationships;

    protected $fillable = [
        'name',
        'ionicon'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function areas() {
        return $this->hasMany(BenefitArea::class);
    }

    public function benefits() {
        return $this->hasManyDeep(
            'App\Benefit',
            ['App\BenefitArea'],
            [null, ['parentable_type', 'parentable_id']]
        );
    }

    public function benefitsWithNoArea() {
        return $this->morphMany(Benefit::class, 'parentable');
    }
}
