<?php

use Illuminate\Database\Seeder;
use App\User;

class MockingStressTestMessageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // just in case heheh
        if (App::environment(['local', 'testing'])) {

            factory(App\Message::class, 300)->create()->each(function ($message) {
                $destinatarios = User::where('approved', '!=', null)
                    ->where('id', "!=", $message->from)
                    ->pluck('id');

                $synchable = [];
                foreach ($destinatarios as $destinatario) {
                    $messageTo['message_id'] = $message->id;
                    $messageTo['user_id'] = $destinatario;
                    $messageTo['read'] = null;
                    $synchable[] = $messageTo;
                }
                $message->to()->attach($synchable);
            });
        }
    }
}
