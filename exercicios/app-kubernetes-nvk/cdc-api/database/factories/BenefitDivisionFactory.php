<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\BenefitDivision;
use App\BenefitArea;
use App\Benefit;
use Faker\Generator as Faker;

$factory->define(BenefitDivision::class, function (Faker $faker) {
    return [
        'name' => $faker->company(),
        'ionicon' => 'bookmarks'
    ];
});

$factory->state(BenefitDivision::class, 'request-areas', function () {
    $areas = factory(BenefitArea::class, 2)->create(); // needs to be create so there is "id" field

    return [
        'areas' => $areas->toArray()
    ];
});

$factory->afterCreatingState(BenefitDivision::class, 'withAreas', function ($division) {

    factory(BenefitArea::class)->create()->each(function ($area) use ($division) {
        $area->division()->associate($division);
        $area->save();
    });

});

$factory->afterCreatingState(BenefitDivision::class, 'withBenefits', function ($division, $faker) {

    factory(BenefitArea::class, 2)->create()->each(function ($area) use ($division) {

        factory(Benefit::class)->create([
            'parentable_id' => $area->id,
            'parentable_type' => BenefitArea::class
        ]);

        $area->division()->associate($division);
        $area->save();
    });

});

$factory->afterCreatingState(BenefitDivision::class, 'withOrphanBenefits', function ($division, $faker) {

    factory(Benefit::class)->state('orphan')->create([
        'parentable_id' => $division->id
    ]);

});
