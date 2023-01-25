<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Campaign;
use Faker\Generator as Faker;

$factory->define(Campaign::class, function (Faker $faker) {
    return [
        'title' => $faker->title(),
        'slug' => $faker->slug(),
        'description' => $faker->text(50),
        'entry_date' => $faker->date(),
        'departure_date' => $faker->date()
    ];
});


$factory->state(Campaign::class, 'past', function ($faker) {
    return [
        'entry_date' => $faker->dateTimeBetween($startDate = '-6 days', $endDate = '-5 days')->format('Y-m-d'),
        'departure_date' => $faker->dateTimeBetween($startDate = '-3 days', $endDate = '-2 days')->format('Y-m-d'),
    ];
});

$factory->state(Campaign::class, 'future', function ($faker) {
    return [
        'entry_date' => $faker->dateTimeBetween($startDate = '+2 days', $endDate = '+3 days')->format('Y-m-d'),
        'departure_date' => $faker->dateTimeBetween($startDate = '+5 days', $endDate = '+6 days')->format('Y-m-d'),
    ];
});

$factory->state(Campaign::class, 'active', function ($faker) {
    return [
        'entry_date' => $faker->dateTimeBetween($startDate = '-3 days', $endDate = '-2 days')->format('Y-m-d'),
        'departure_date' => $faker->dateTimeBetween($startDate = '+2 days', $endDate = '+3 days')->format('Y-m-d'),
    ];
});
