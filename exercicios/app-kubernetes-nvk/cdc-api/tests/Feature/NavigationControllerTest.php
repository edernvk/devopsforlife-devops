<?php

namespace Tests\Feature;

use App\BurguesaJacketCampaign;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class NavigationControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function nonAuthUsersAccessUser() {
        $retrieve = $this->json('GET', '/api/navigation');
        $retrieve->assertStatus(401);
    }

    /** @test */
    public function canRetrieve() {
        Carbon::setTestNow('2021-08-13 23:59:59');

        $response = $this->actingAs($this->admin, 'api')->json('GET', '/api/navigation');
        $response
            ->assertStatus(200)
            ->dump()
            ->assertJsonCount(1);
    }

    /** @test */
    public function cannotRetrieve() {
        Carbon::setTestNow('2021-08-14 00:00:00');

        $response = $this->actingAs($this->admin, 'api')->json('GET', '/api/navigation');
        $response
            ->assertStatus(200)
            ->dump()
            ->assertJsonCount(0);
    }
}
