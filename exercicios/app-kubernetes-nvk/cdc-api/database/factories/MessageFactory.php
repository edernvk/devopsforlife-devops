<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use Carbon\Carbon;
use Faker\Generator as Faker;
use App\Message;

$factory->define(Message::class, function (Faker $faker) {
    return [
        'from' => User::inRandomOrder()->first(),
        'title' => $faker->text(25),
        'description' => $faker->paragraphs(3, true),
        'status_id' => \App\StatusMessage::PUBLISHED,
        'publish_datetime' => new \DateTime()
    ];
});

$factory->state(Message::class, 'from-admin', function() {
    return [
        'from' => User::where('email', 'admin@penze.com.br')->first()->id
    ];
});

$factory->state(Message::class, 'from-bot', function () {
    return [
        'from' => User::where('email', 'bot@penze.com.br')->first()->id
    ];
});

$factory->state(Message::class, 'from-comunicacao-interna', function () {
    $comunicacao = (User::where('email', 'comunicacao@example.com')->first()) ?? factory(User::class)->state('user-comunicacao-interna')->create();

    return [
        'from' => $comunicacao->id
    ];
});

$factory->state(Message::class, 'as-draft', [
    'status_id' => \App\StatusMessage::DRAFT
]);

$factory->state(Message::class, 'as-inactive', [
    'status_id' => \App\StatusMessage::INACTIVE
]);

$factory->afterCreatingState(Message::class, 'to-everybody', function ($message) {
    $destinatarios = User::where('approved', '!=', null)
        ->where('id', "!=", $message->from)
        ->pluck('id');

    $synchable = [];
    foreach ($destinatarios as $destinatario) {
        $messageTo['message_id'] = $message->id;
        $messageTo['read'] = null;
        $messageTo['user_id'] = $destinatario;
        $synchable[] = $messageTo;
    }
    $message->to()->attach($synchable);
});

$factory->afterCreatingState(Message::class, 'to-somebody', function ($message) {
    $newDestinatario = factory(User::class)->create();

    $synchable = [];
    $messageTo['message_id'] = $message->id;
    $messageTo['read'] = null;
    $messageTo['user_id'] = $newDestinatario->id;
    $synchable[] = $messageTo;

    $message->to()->attach($synchable);
});

$factory->afterCreatingState(Message::class, 'all-read', function ($message) {
    $message->load('to');

    foreach ($message->to as $destinatario) {
        $message->to()->updateExistingPivot($destinatario->id, ['read' => Carbon::now()]);
    }
});
