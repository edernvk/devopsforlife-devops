<?php

namespace Tests\Feature;

use App\Campaign;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CampaignControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function canReturnActiveCampaign()
    {
        $campaign = factory(Campaign::class)->state('active')->create();

        $response = $this->actingAs($this->notAdmin, 'api')
            ->json('GET', '/api/campaigns/' . $campaign->slug);
        $response
            ->assertStatus(200)
            ->assertJsonStructureExact([
                'id',
                'title',
                'description',
                'slug',
                'entry_date',
                'departure_date',
                'created_at',
                'updated_at'
            ]);
    }

    /** @test */
    public function shouldReturnInactiveCampaign()
    {
        $campaign = factory(Campaign::class)->state('future')->create();

        $response = $this->actingAs($this->notAdmin, 'api')
            ->json('GET', '/api/campaigns/' . $campaign->slug);
        $response
            ->assertStatus(406)
            ->assertJsonStructureExact([
                'error' => [
                    'description',
                    'message'
                ]
            ])
            ->assertJsonFragment([
                'message' => 'Esta campanha ainda não está ativa.'
            ]);
    }

    /** @test */
    public function shouldReturnFinishedCampaign()
    {
        $campaign = factory(Campaign::class)->state('past')->create();

        $response = $this->actingAs($this->notAdmin, 'api')
            ->json('GET', '/api/campaigns/' . $campaign->slug);
        $response
            ->assertStatus(406)
            ->assertJsonStructureExact([
                'error' => [
                    'description',
                    'message'
                ]
            ])
            ->assertJsonFragment([
                'message' => 'Esta campanha já foi encerrada.'
            ]);
    }
}
