<?php

use App\Campaign;
use Illuminate\Database\Seeder;

class ChristmasBasketNotFilledCampaign extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $campaign = Campaign::where('slug', 'cesta-natal')->first();
        $campaign->update([
            'departure_date' => '2021-12-06'
        ]);
    }
}
