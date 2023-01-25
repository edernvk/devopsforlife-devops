<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExtensionDivision extends Model
{
    use \Staudenmeir\EloquentHasManyDeep\HasRelationships;

    protected $fillable = [
        'name',
        'color'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];


    public function areas() {
        return $this->hasMany(ExtensionArea::class);
    }

    public function numbers() {
        return $this->hasManyDeep(
            'App\ExtensionNumber',
            ['App\ExtensionArea'],
            [null, ['parentable_type', 'parentable_id']]
        );
    }

    public function numbersWithNoArea() {
        return $this->morphMany(ExtensionNumber::class, 'parentable');
    }

}
