<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DrawingContestCategory extends Model
{
    use \Staudenmeir\EloquentEagerLimit\HasEagerLimit;

    protected $fillable = [
        'name'
    ];

    public function pictures() 
    {
        return $this->hasMany(DrawingContestPicture::class, 'category_id', 'id');
    }
}
