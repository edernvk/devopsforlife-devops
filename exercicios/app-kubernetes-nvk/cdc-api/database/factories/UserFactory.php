<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;
use Faker\Provider\pt_BR\PhoneNumber;
use Faker\Provider\pt_BR\Person;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {

    $faker->addProvider(new PhoneNumber($faker));
    $faker->addProvider(new Person($faker));

    $lotto = ($faker->randomDigit() >= 6);
    $today = \Carbon\Carbon::today();

    return [
        'name' => $faker->name(),
        'cpf' => $faker->cpf(false),
        'email' => $faker->unique()->safeEmail(),
        'approved' => ($lotto) ? $today->toDateTimeString() : null,
        'email_verified_at' => ($lotto) ? $today->toDateTimeString() : null,
        'registration' => $faker->cpf(false),
        'mobile' => $faker->phoneNumberCleared(),
        'avatar' => null,
        'city_id' => \App\City::inRandomOrder()->first() ?? factory(\App\City::class)->create(),
        'team_id' => \App\Team::inRandomOrder()->first() ?? factory(\App\Team::class)->create(),
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'remember_token' => Str::random(10),
        'birth_date' => $faker->dateTimeBetween('-1 year', 'now')->format('d-m')
    ];
});

$factory->state(\App\User::class, 'user-comunicacao-interna', [
    'name' => 'Comunicação Interna',
    'cpf' => '00000000000',
    'email' => 'comunicacao@example.com',
    'mobile' => '12345678910',
    'avatar' => 'http://cdc-api.test/storage/users-avatars/bot.png'
]);

$factory->state(\App\User::class, 'approved', [
    'approved' => now()->toDateTimeString()
]);
$factory->state(\App\User::class, 'allowed-terms', [
    'allow_terms' => now()->toDateTimeString()
]);
$factory->state(\App\User::class, 'not-first-time', [
    'first_time' => now()->toDateTimeString()
]);

$factory->afterCreatingState(App\User::class, 'admin', function ($user) {
    $role = factory(App\Role::class)->states('admin')->make();
    $user->roles()->save(App\Role::firstOrCreate($role->toArray()));
});

$factory->afterCreatingState(App\User::class, 'colaborador', function ($user) {
    $role = factory(App\Role::class)->states('colaborador')->make();
    $user->roles()->save(App\Role::firstOrCreate($role->toArray()));
});

$factory->afterCreatingState(App\User::class, 'aniversario-hoje', function ($user) {
    $date = now()->format('d-m');
    $user->birth_date = $date;
    $user->save();
});

$factory->afterCreatingState(App\User::class, 'aniversario-amanha', function ($user) {
    $date = now()->addDay()->format('d-m');
    $user->birth_date = $date;
    $user->save();
});
