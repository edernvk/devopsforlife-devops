<?php

use App\Campaign;
use App\CupShirtProducts;
use Illuminate\Database\Seeder;

class MockingCupShirtSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Campaign::create([
        //     'title' => 'Camisa Copa',
        //     'slug' => 'camisa-copa',
        //     'description' => 'Campanha para comprar sua camisa da copa',
        //     'entry_date' => '2022-09-01',
        //     'departure_date' => '2022-09-15'
        // ]);

        CupShirtProducts::create([
            'name' => 'Conti Cola',
            'amount' => '10.00'
        ]);
        CupShirtProducts::create([
            'name' => 'Smith44',
            'amount' => '20.00'
        ]);
    }
}
