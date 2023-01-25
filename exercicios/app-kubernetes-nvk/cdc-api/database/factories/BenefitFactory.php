<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\BenefitDivision;
use App\BenefitArea;
use App\Benefit;
use Faker\Generator as Faker;

$factory->define(Benefit::class, function (Faker $faker) {
    return [
        'partner' => $faker->company(),
        'contact' => (string) $faker->numberBetween(1000,10000),
        'benefit' => $faker->text(25),
        'parentable_id' => factory(BenefitArea::class)->create()->id,
        'parentable_type' => BenefitArea::class
    ];
});

$factory->state(Benefit::class, 'orphan', function() {
    $division = BenefitDivision::first() ?? factory(BenefitDivision::class)->create();

    return [
        'parentable_id' => $division->id,
        'parentable_type' => BenefitDivision::class,
    ];
});

$factory->state(Benefit::class, 'request', function ($faker) {
    $division = factory(BenefitDivision::class)->state('withAreas')->create();
    $area = $division->areas()->first(); // created by the division factory previous line

    return [
        'division_id' => $division->id,
        'area_id' => $area->id,
        'parentable_id' => $area->id,
        'parentable_type' => BenefitArea::class,
    ];
});

$factory->state(Benefit::class, 'request-orphan', function ($faker) {
    $division = factory(BenefitDivision::class)->create();

    return [
        'area_id' => null,
        'division_id' => $division->id,
        'parentable_id' => $division->id,
        'parentable_type' => BenefitDivision::class,
    ];
});
