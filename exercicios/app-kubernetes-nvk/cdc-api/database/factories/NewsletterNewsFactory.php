<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Helpers\FakerPicsumProvider;
use App\Model;
use App\NewsletterNews;
use Faker\Generator as Faker;

$factory->define(NewsletterNews::class, function (Faker $faker) {
    $faker->addProvider(new FakerPicsumProvider($faker));

    static $loop = 0;
    $increasingTimestamp = now()->copy()->addSeconds($loop++);

    return [
        'title' => $faker->word(),
        'content' => $faker->text(),
        'thumbnail' => $faker->imageUrl(),
        'user_id' => \App\User::inRandomOrder()->value('id'),
        'created_at' => $increasingTimestamp,
        'updated_at' => $increasingTimestamp
    ];
});
