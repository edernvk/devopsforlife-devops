<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use App\Helpers\FakerPicsumProvider;
use App\Newsletter;

$factory->define(Newsletter::class, function (Faker $faker) {

    $faker->addProvider(new FakerPicsumProvider($faker));

    static $loop = 0;
    $increasingTimestamp = now()->copy()->addSeconds($loop++);

    return [
        'name' => $faker->sentence(3),
        'cover' => $faker->picsumStaticRandomUrl(1240, 1754),
        'created_at' => $increasingTimestamp,
        'updated_at' => $increasingTimestamp
    ];

});
