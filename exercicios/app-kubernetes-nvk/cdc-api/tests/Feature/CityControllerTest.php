<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CityControllerTest extends TestCase
{
    public function setUp(): void {
        parent::setUp();
        $this->artisan('migrate:fresh --seed');
    }

    /** @test */
    public function canListAllCitiesFromState() {
        $response = $this->json('GET', '/api/cities/state/1');

        $response
            ->assertStatus(200)
            ->assertJsonStructureExact([
                'state' => [
                    'id',
                    'state',
                    'acronym'
                ],
                'cities' => [
                    '*' => [
                        'id',
                        'name',
                        'state_id'
                    ]
                ]
            ]);
    }
}
