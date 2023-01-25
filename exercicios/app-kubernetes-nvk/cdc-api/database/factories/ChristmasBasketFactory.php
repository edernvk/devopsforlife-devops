<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\ChristmasBasket;
use Faker\Generator as Faker;

$factory->define(ChristmasBasket::class, function (Faker $faker) {
    $kinship = [
        'Mãe',
        'Pai',
        'Irmão (a)',
        'Filho (a)',
        'Cunhado (a)',
        'Tio (a)',
        'Avô (a)',
        'Sobrinho (a)',
        'Genro',
        'Nora',
        'Sogro (a)',
        'Amigo (a)',
        'Vizinho (a)',
        'Colega de Trabalho',
        'Outro'
    ];

    return [
        'user_id' => \App\User::inRandomOrder()->first()->id,
        'shipping_address_street_name' => $faker->streetName,
        'shipping_address_number' => $faker->buildingNumber,
        'shipping_address_neighbourhood' => $faker->citySuffix,
        'shipping_address_zipcode' => $faker->postcode,
        'shipping_address_city' => $faker->city,
        'shipping_address_complement' => $faker->citySuffix,
        'name_recipient' => $faker->name,
        'degree_kinship' => $kinship[mt_rand(0, count($kinship) - 1)],
        'suggestion' => $faker->text(),
    ];
});

$factory->state(ChristmasBasket::class, 'complementless', [
    'shipping_address_complement' => null,
]);
