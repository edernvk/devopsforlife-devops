<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$now = now();

$factory->define(UserVideocastTracked::class, function (Faker $faker) use ($now) {
    return [
        'user_id' => $faker->id(),
        'videocast_id' => $faker->id(),
        'participation' => now()->format('d/m/Y - H:i')
    ];
});
