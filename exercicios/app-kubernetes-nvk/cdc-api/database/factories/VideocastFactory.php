<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Videocast;
use Faker\Generator as Faker;

$now = now();

$factory->define(Videocast::class, function (Faker $faker) use ($now) {
    return [
        'title' => $faker->text(25),
        'description' => $faker->paragraphs(1, true),
        'video_url' => 'https://player.vimeo.com/video/421529197',
        'date' => $now->addMinute()->format('d/m/Y - H:i \h\r\s'),
        'trackeable' => $faker->boolean(50),
    ];
});

/*
    // Converts all videocasts.`date` from `DD/MM/AAAA hh:mm hrs` to `AAAA-MM-DD hh:mm:ss`
    \App\Videocast::all()->each(function (\App\Videocast $videocast) {
        $videocast->setRawAttributes([
            'date' => \Carbon\Carbon::createFromFormat('d/m/Y - H:i \h\r\s', $videocast->getOriginal('date'))->toDateTimeString()
        ]);
        $videocast->save();
    });
*/
