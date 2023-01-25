<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\ExtensionDivision;
use App\ExtensionArea;
use App\ExtensionNumber;
use Faker\Generator as Faker;

$factory->define(ExtensionArea::class, function (Faker $faker) {
    return [
        'name' => $faker->jobTitle(),
        'extension_division_id' => ExtensionDivision::first()->id ?? factory(ExtensionDivision::class)->create()->id
    ];
});

$factory->afterCreatingState(ExtensionArea::class, 'withNumbers', function ($area) {

    factory(ExtensionNumber::class)->create([
        'parentable_id' => $area->id,
        'parentable_type' => ExtensionArea::class
    ]);

});
