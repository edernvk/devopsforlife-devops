<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Magazine;
use App\Helpers\FakerPicsumProvider;
use Faker\Generator as Faker;

$factory->define(Magazine::class, function (Faker $faker) {

    $faker->addProvider(new FakerPicsumProvider($faker));

    static $loop = 0;
    $increasingTimestamp = now()->copy()->addSeconds($loop++);

    return [
        'title' => $faker->sentence(5),
        'link' => $faker->url,
        'cover' => $faker->picsumStaticRandomUrl(1240, 1754),
        'created_at' => $increasingTimestamp,
        'updated_at' => $increasingTimestamp
    ];

});
