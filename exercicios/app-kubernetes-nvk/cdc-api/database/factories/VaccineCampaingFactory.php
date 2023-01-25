<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\VaccineCampaing;
use Faker\Generator as Faker;

$factory->define(VaccineCampaing::class, function (Faker $faker) {
    $choses = [
        'SIM', 'NÃO'
    ];

    return [
        'user_id' => \App\User::inRandomOrder()->first()->id,
        'confirmation' => $choses[mt_rand(0, count($choses) - 1)],
        'authorize' => $faker->boolean()
    ];
});
