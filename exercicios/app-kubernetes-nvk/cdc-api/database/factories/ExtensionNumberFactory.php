<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\ExtensionArea;
use App\ExtensionDivision;
use App\ExtensionNumber;
use Faker\Generator as Faker;

$factory->define(ExtensionNumber::class, function (Faker $faker) {
    return [
        'name' => $faker->company(),
        'number' => (string) $faker->numberBetween(1000,10000),
        'parentable_id' => factory(ExtensionArea::class)->create()->id,
        'parentable_type' => ExtensionArea::class
    ];
});

$factory->state(ExtensionNumber::class, 'orphan', function ($faker) {
    $division = ExtensionDivision::first() ?? factory(ExtensionDivision::class)->create();

    return [
        'parentable_id' => $division->id,
        'parentable_type' => ExtensionDivision::class,
    ];
});

$factory->state(ExtensionNumber::class, 'request', function ($faker) {
    $division = factory(ExtensionDivision::class)->state('withAreas')->create();
    $area = $division->areas()->first(); // created by the division factory previous line

    return [
        'division_id' => $division->id,
        'area_id' => $area->id,
        'parentable_id' => $area->id,
        'parentable_type' => ExtensionArea::class,
    ];
});

$factory->state(ExtensionNumber::class, 'request-orphan', function ($faker) {
    $division = factory(ExtensionDivision::class)->create();

    return [
        'area_id' => null,
        'division_id' => $division->id,
        'parentable_id' => $division->id,
        'parentable_type' => ExtensionDivision::class,
    ];
});
