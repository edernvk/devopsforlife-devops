<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Role;
use Faker\Generator as Faker;

$factory->define(Role::class, function (Faker $faker) {
    return [
        'name' => $faker->jobTitle(),
        'description' => $faker->sentence(2)
    ];
});

$factory->state(Role::class, 'admin', [
    'name' => 'Administrador',
    'description' => 'Administrador do CDC Digital'
]);

$factory->state(Role::class, 'colaborador', [
    'name' => 'Colaborador',
    'description' => 'Colaborador da Casa di Conti'
]);
