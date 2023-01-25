<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\BurguesaJacketCampaign;
use Faker\Generator as Faker;

$factory->define(BurguesaJacketCampaign::class, function (Faker $faker) {
    return [
        'jacket_1_size' => BurguesaJacketCampaign::JACKET_SIZES[array_rand(BurguesaJacketCampaign::JACKET_SIZES)],
        'jacket_2_size' => BurguesaJacketCampaign::JACKET_SIZES[array_rand(BurguesaJacketCampaign::JACKET_SIZES)],
        'installments_amount' => mt_rand(1, BurguesaJacketCampaign::MAX_INSTALLMENTS),
        'payment_agreement' => now()->toDateTimeString()
    ];
});

$factory->state(BurguesaJacketCampaign::class, 'fromRequest', [
    'payment_agreement' => true
]);

$factory->afterCreatingState(BurguesaJacketCampaign::class, 'withUser', function ($entry) {

    $entry->user()->associate($entry->user_id);

});
