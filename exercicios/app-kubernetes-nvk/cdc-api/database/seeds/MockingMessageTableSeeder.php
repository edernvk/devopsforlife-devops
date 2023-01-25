<?php

use App\User;
use Illuminate\Database\Seeder;

class MockingMessageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Message::class, 50)->create()->each(function ($message) {
            $destinatarios = User::inRandomOrder()
                ->where('approved', '!=', null)
                ->where('id', "!=", $message->from)
                ->take(10)->pluck('id');

            $synchable = [];
            foreach ($destinatarios as $destinatario) {
                $messageTo['message_id'] = $message->id;
                $messageTo['read'] = null;
                $messageTo['user_id'] = $destinatario;
                $synchable[] = $messageTo;
            }
            $message->to()->attach($synchable);
        });

        factory(App\Message::class, 2)->make()->each(function ($message) {
            $message->description = '<a href="http://google.com.br" target="_blank">www.google.com.br</a>';
            $message->save();

            $destinatarios = User::inRandomOrder()
                ->where('approved', '!=', null)
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
    }
}
