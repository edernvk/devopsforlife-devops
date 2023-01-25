<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\PaycheckAccess;
use Faker\Generator as Faker;

$factory->define(PaycheckAccess::class, function (Faker $faker) {
    return [
        'email' => $faker->email(),
        'password' => 'paycheckpassword123'
    ];
});
