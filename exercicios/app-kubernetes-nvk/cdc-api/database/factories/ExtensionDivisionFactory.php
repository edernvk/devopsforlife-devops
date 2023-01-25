<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\ExtensionArea;
use App\ExtensionDivision;
use App\ExtensionNumber;
use Faker\Generator as Faker;

$factory->define(ExtensionDivision::class, function (Faker $faker) {
    return [
        'name' => $faker->company(),
        'color' => $faker->hexcolor()
    ];
});

$factory->state(ExtensionDivision::class, 'request-areas', function ($faker) {
    $areas = factory(ExtensionArea::class, 2)->create(); // needs to be create so there is "id" field

    return [
        'areas' => $areas->toArray()
    ];
});

$factory->afterCreatingState(ExtensionDivision::class, 'withAreas', function ($division) {

    factory(ExtensionArea::class)->create()->each(function ($area) use ($division) {
        $area->division()->associate($division);
        $area->save();
    });

});

$factory->afterCreatingState(ExtensionDivision::class, 'withNumbers', function ($division, $faker) {

    factory(ExtensionArea::class, 2)->create()->each(function ($area) use ($division) {

        factory(ExtensionNumber::class)->create([
            'parentable_id' => $area->id,
            'parentable_type' => ExtensionArea::class
        ]);

        $area->division()->associate($division);
        $area->save();
    });

});

$factory->afterCreatingState(ExtensionDivision::class, 'withOrphanNumbers', function ($division, $faker) {

    factory(ExtensionNumber::class)->state('orphan')->create([
        'parentable_id' => $division->id
    ]);

});
