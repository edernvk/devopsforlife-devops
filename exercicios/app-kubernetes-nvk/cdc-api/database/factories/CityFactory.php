<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\City;
use App\State;
use Faker\Generator as Faker;

$factory->define(City::class, function (Faker $faker) {
    return [
        'name' => $faker->city(),
        'state_id' => State::inRandomOrder()->first() ?? factory(State::class)->create()
    ];
});
