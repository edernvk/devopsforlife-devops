<?php

use Illuminate\Database\Seeder;
use anlutro\LaravelSettings\Facade as Settings;
use Illuminate\Support\Facades\Storage;

class MockingSimplifiedBenefitsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $benefit = [
            'message' => '<ul><li>Novo benefício 1: teste</li><li>Novo benefício 2: teste2</li></ul>',
            'poster' => Storage::url('benefits-posters/160103715911.jpg')
        ];

        Settings::set('simplified_benefits.message', $benefit['message']);
        Settings::set('simplified_benefits.poster', $benefit['poster']);
        Settings::set('simplified_benefits.updated_at', now());
        Settings::save();
    }
}
