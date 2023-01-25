<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\DrawingContestCategory;
use App\Model;
use Faker\Generator as Faker;

$factory->define(DrawingContestCategory::class, function (Faker $faker) {
    return [
        'name' => $faker->word(),
    ];
});
