<?php

namespace Tests\Feature;

use App\BurguesaJacketCampaign;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BurguesaJacketCampaignControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function nonAuthUsersAccessUser() {
        $retrieve = $this->json('GET', '/api/campaigns/burguesa-jacket');
        $retrieve->assertStatus(401);

        $create = $this->json('POST', '/api/campaigns/burguesa-jacket');
        $create->assertStatus(401);

//        $destroy = $this->json('DELETE', '/api/campaigns/burguesa-jacket');
//        $destroy->assertStatus(401);
    }

    /** @test */
    public function canCreate() {
        Carbon::setTestNow('2021-04-29 23:59:00');

        $payload = factory(BurguesaJacketCampaign::class)->state('fromRequest')->make();

        $response = $this->actingAs($this->admin, 'api')->json('POST', '/api/campaigns/burguesa-jacket', $payload->toArray());
        $response
            ->assertStatus(200)
            ->assertJsonStructureExact([
                'id',
                'jacket_1_size',
                'jacket_2_size',
                'payment_agreement',
                'installments_amount',
                'user_id',
                'updated_at',
                'created_at',
            ]);

        $payload = collect($payload)->forget(['payment_agreement']);
        $this->assertDatabaseHas('campaigns_burguesa_jacket', $payload->toArray());
    }

    /** @test */
    public function cannotCreate() {
        Carbon::setTestNow('2021-04-30 00:00:00');

        $payload = factory(BurguesaJacketCampaign::class)->state('fromRequest')->make();

        $response = $this->actingAs($this->admin, 'api')->json('POST', '/api/campaigns/burguesa-jacket', $payload->toArray());
        $response->assertStatus(406);

        $payload = collect($payload)->forget(['payment_agreement']);
        $this->assertDatabaseMissing('campaigns_burguesa_jacket', $payload->toArray());
    }

    /** @test */
    public function canRetrieve() {
        Carbon::setTestNow('2021-04-29 23:59:00');

        $entry = factory(BurguesaJacketCampaign::class)->state('withUser')->create([
            'user_id' => $this->admin->id
        ]);

        $response = $this->actingAs($this->admin, 'api')->json('GET', '/api/campaigns/burguesa-jacket');
        $response
            ->assertStatus(200)
            ->assertJsonStructureExact([
                'id',
                'jacket_1_size',
                'jacket_2_size',
                'payment_agreement',
                'installments_amount',
                'user_id',
                'updated_at',
                'created_at',
            ]);
    }

    /** @test */
    public function cannotRetrieve() {
        Carbon::setTestNow('2021-04-30 00:00:00');

        $entry = factory(BurguesaJacketCampaign::class)->state('withUser')->create([
            'user_id' => $this->admin->id
        ]);

        $response = $this->actingAs($this->admin, 'api')->json('GET', '/api/campaigns/burguesa-jacket');
        $response->assertStatus(406);

    }
}
