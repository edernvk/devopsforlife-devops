<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\BenefitDivision;
use App\BenefitArea;
use App\Benefit;
use Faker\Generator as Faker;

$factory->define(BenefitArea::class, function (Faker $faker) {
    return [
        'name' => $faker->jobTitle(),
        'benefit_division_id' => BenefitDivision::first()->id ?? factory(BenefitDivision::class)->create()->id
    ];
});

$factory->afterCreatingState(BenefitArea::class, 'withBenefits', function ($area) {

    factory(Benefit::class)->create([
        'parentable_id' => $area->id,
        'parentable_type' => BenefitArea::class
    ]);

});
