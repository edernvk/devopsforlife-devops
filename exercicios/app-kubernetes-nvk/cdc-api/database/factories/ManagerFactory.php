<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\City;
use App\Manager;
use App\Model;
use Faker\Generator as Faker;

$factory->define(Manager::class, function (Faker $faker) {
    return [
        'name' => $faker->name(),
        'type' => $faker->word(),
        'email' => $faker->email
    ];
});

