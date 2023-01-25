<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\VaccineSurveyCampaign;
use Faker\Generator as Faker;

$factory->define(VaccineSurveyCampaign::class, function (Faker $faker) {
    return [
        'local_age_reached' => VaccineSurveyCampaign::BOOLEAN_OPTIONS[array_rand(VaccineSurveyCampaign::BOOLEAN_OPTIONS)],
        'first_dose' => VaccineSurveyCampaign::BOOLEAN_OPTIONS[array_rand(VaccineSurveyCampaign::BOOLEAN_OPTIONS)],
        'second_dose' => VaccineSurveyCampaign::BOOLEAN_OPTIONS_EXTRA[array_rand(VaccineSurveyCampaign::BOOLEAN_OPTIONS_EXTRA)],
    ];
});

$factory->afterCreatingState(VaccineSurveyCampaign::class, 'withUser', function ($entry) {

    $entry->user()->associate($entry->user_id);

});


$factory->state(VaccineSurveyCampaign::class, 'no-dose', [
    'first_dose' => 'no',
    'second_dose' => 'no'
]);

$factory->state(VaccineSurveyCampaign::class, 'both-doses', [
    'first_dose' => 'yes',
    'second_dose' => 'yes'
]);

$factory->state(VaccineSurveyCampaign::class, 'first-dose', [
    'first_dose' => 'yes',
    'second_dose' => 'no'
]);

$factory->state(VaccineSurveyCampaign::class, 'single-dose', [
    'first_dose' => 'yes',
    'second_dose' => 'n/a'
]);
