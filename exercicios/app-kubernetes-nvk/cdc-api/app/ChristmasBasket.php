<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChristmasBasket extends Model
{
    protected $fillable = [
        'user_id',
        'shipping_address_zipcode',
        'shipping_address_street_name',
        'shipping_address_number',
        'shipping_address_neighbourhood',
        'shipping_address_city',
        'shipping_address_complement',
        'name_recipient',
        'degree_kinship',
        'suggestion'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
