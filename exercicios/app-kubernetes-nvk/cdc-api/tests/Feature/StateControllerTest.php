<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\State;

class StateControllerTest extends TestCase
{
    public function setUp(): void {
        parent::setUp();
        $this->artisan('migrate:fresh --seed');
    }

    /** @test */
    public function forbiddenStatesEndpoints() {
        $store = $this->json('POST', '/api/states');
        $store->assertStatus(403);

        $update = $this->json('PUT', '/api/states/-1');
        $update->assertStatus(403);

        $destroy = $this->json('DELETE', '/api/states/-1');
        $destroy->assertStatus(403);
    }

    /** @test */
    public function canShowAllStates() {
        $response = $this->json('GET', '/api/states');

        $response
            ->assertStatus(200)
            ->assertJsonStructureExact([
                '*' => [
                    'id',
                    'acronym',
                    'state'
                ]
            ]);
    }

    /** @test */
    public function canReturnState() {
        $response = $this->json('GET', "api/states/1");

        $state = State::find(1);

        $response
            ->assertStatus(200)
            ->assertJsonFragment($state->toArray());
    }
}
