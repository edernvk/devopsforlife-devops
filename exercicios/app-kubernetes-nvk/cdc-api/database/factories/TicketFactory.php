<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Ticket;
use App\User;
use Faker\Generator as Faker;
use Faker\Provider\pt_BR\Address;
use Faker\Provider\pt_BR\Person;
use Illuminate\Support\Str;

$factory->define(Ticket::class, function (Faker $faker) {

    $kinship = ["Pai", "Mãe", "Filho", "Filha", "Tio", "Tia", "Irmão", "Irmã"];

    $faker->addProvider(new Address($faker));
    $faker->addProvider(new Person($faker));

    $user = User::inRandomOrder()->first();

    return [
        "user_id" => $user->id,
        "coupon" => Str::uuid(),
        "name" => $user->name,
        "email" => $user->email,
        "phone" => $user->mobile,
        "city_id" => $user->city_id,
        "suggestion" => $faker->paragraphs(1, true),
        "address_street_name" => $faker->streetName(),
        "address_number" => $faker->buildingNumber(),
        "address_neighbourhood" => $faker->citySuffix(),
        "address_postal_code" => preg_replace("/[^0-9]/", "", $faker->postcode()),
        "shipping_address_street_name" => $faker->streetName(),
        "shipping_address_number" => $faker->buildingNumber(),
        "shipping_address_neighbourhood" => $faker->citySuffix(),
        "shipping_address_postal_code" => preg_replace("/[^0-9]/", "", $faker->postcode()),
        "shipping_address_complement" => $faker->citySuffix(),
        "shipping_address_city_id" => $user->city_id,
        "shipping_address_recipient" => $faker->firstName().' '.$faker->lastName(),
        "shipping_address_recipient_kinship" => $kinship[mt_rand(0, count($kinship) - 1)]
    ];
});

$factory->state(Ticket::class, 'complementless', [
    'shipping_address_complement' => null
]);
