<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\State;
use Faker\Generator as Faker;

$factory->define(State::class, function (Faker $faker) {
    return [
        'state' => $faker->state(),
        'acronym' => strtoupper($faker->languageCode()) // there was no stateCode...
    ];
});
