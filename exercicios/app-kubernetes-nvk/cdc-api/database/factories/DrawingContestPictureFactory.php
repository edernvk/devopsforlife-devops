<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\DrawingContestCategory;
use App\DrawingContestPicture;
use Faker\Generator as Faker;

$factory->define(DrawingContestPicture::class, function (Faker $faker) {
    $category = DrawingContestCategory::inRandomOrder()->first() ?? factory(DrawingContestCategory::class)->create();
    return [
        'category_id' => $category->id,
        'url' => $faker->imageUrl(),
        'subscription' => $faker->word()
    ];
});
