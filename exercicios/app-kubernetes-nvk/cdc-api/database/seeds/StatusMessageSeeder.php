<?php

use Illuminate\Database\Seeder;
use App\StatusMessage;

class StatusMessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        StatusMessage::create([
            'status' => "Inativo",
        ]);

        StatusMessage::create([
            'status' => "Rascunho",
        ]);

        StatusMessage::create([
            'status' => "Publicado",
        ]);
    }
}
