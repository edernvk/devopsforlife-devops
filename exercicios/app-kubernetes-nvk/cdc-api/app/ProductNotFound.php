<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductNotFound extends Model
{
    public $table = "products_not_found";

    protected $fillable = [
        'establishment_name',
        'establishment_address',
        'city_id',
        'state_id',
        'user_id',
        'product_id'
    ];

    public function city()
    {
        return $this->belongsTo(City::class);
    }
    public function state()
    {
        return $this->belongsTo(State::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->belongsToMany(
            Product::class,
            'products_not_found_product_pivot',
            'products_not_found_id',
            'product_id',
        );
    }
}
