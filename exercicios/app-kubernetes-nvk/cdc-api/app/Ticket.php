<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $table = "tickets";

    protected $fillable = [
        "user_id", // fk
        "city_id", // fk
//        "coupon",
        "name",
        "email",
        "phone",
        "address_street_name",
        "address_number",
        "address_neighbourhood",
        "address_postal_code",
        "shipping_address_street_name",
        "shipping_address_number",
        "shipping_address_neighbourhood",
        "shipping_address_postal_code",
        "shipping_address_complement",
        "shipping_address_city_id", // fk
        "shipping_address_recipient",
        "shipping_address_recipient_kinship",
        "suggestion"
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function city() {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function shippingCity() {
        return $this->belongsTo(City::class, 'shipping_address_city_id');
    }
}
