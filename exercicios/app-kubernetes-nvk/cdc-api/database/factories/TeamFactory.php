<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Team;
use Faker\Generator as Faker;
use Faker\Provider\en_US\Company;

$factory->define(Team::class, function (Faker $faker) {

    $faker->addProvider(new Company($faker));

    return [
        'name' => $faker->jobTitle()
    ];
});
