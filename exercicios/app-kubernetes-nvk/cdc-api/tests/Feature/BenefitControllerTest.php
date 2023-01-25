<?php

namespace Tests\Feature;

use App\Benefit;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BenefitControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function shouldBlockUnauthenticatedAccess() {
        $index = $this->json('GET', '/api/partners/benefits/1');
        $index->assertStatus(401);

        $update = $this->json('POST', '/api/partners/benefits');
        $update->assertStatus(401);

        $destroy = $this->json('PUT', '/api/partners/benefits/-1');
        $destroy->assertStatus(401);

        $destroy = $this->json('DELETE', '/api/partners/benefits/-1');
        $destroy->assertStatus(401);

        $store = $this->json('GET', '/api/partners/benefits/all');
        $store->assertStatus(401);
    }

    /** @test */
    public function shouldBlockForbiddenAccess() {
        $benefit = factory(Benefit::class)->create();
        $payload = factory(Benefit::class)->state('request')->make();

        $store = $this->actingAs($this->notAdmin, 'api')->json('POST', '/api/partners/benefits', $payload->toArray());
        $store->assertStatus(403);

        $update = $this->actingAs($this->notAdmin, 'api')->json('PUT', '/api/partners/benefits/'.$benefit->id, $payload->toArray());
        $update->assertStatus(403);

        $destroy = $this->actingAs($this->notAdmin, 'api')->json('DELETE', '/api/partners/benefits/'.$benefit->id);
        $destroy->assertStatus(403);
    }

    /** @test */
    public function shouldBlockMethodNotAllowed() {
        $index = $this->json('GET', '/api/partners/benefits');
        $index->assertStatus(405);
    }

    /** @test */
    public function canReturnListAll() {
        factory(Benefit::class, 5)->create();

        $response = $this->actingAs($this->admin, 'api')->json('GET', '/api/partners/benefits/all');
        $response
            ->assertStatus(200)
            ->assertJsonStructureExact([
                'data' => [
                    '*' => [
                        'id',
                        'partner',
                        'contact',
                        'benefit',
                        'area' => [
                            'id',
                            'name'
                        ],
                        'division' => [
                            'id',
                            'name',
                            'ionicon'
                        ]
                    ]
                ]
            ]);
    }

    /** @test */
    public function canReturnListAllOrphans() {
        factory(Benefit::class, 2)->state('orphan')->create();

        $response = $this->actingAs($this->admin, 'api')->json('GET', '/api/partners/benefits/all');
        $response
            ->assertStatus(200)
            ->assertJsonStructureExact([
                'data' => [
                    '*' => [
                        'id',
                        'partner',
                        'contact',
                        'benefit',
                        'division' => [
                            'id',
                            'name',
                            'ionicon'
                        ]
                    ]
                ]
            ]);
    }

    /** @test */
    public function shouldReturnUnprocessableFields() {
        $benefit = factory(Benefit::class)->create();
        $payload = [];
        $payloadWithArea = $benefit;
        $payloadWithArea->division_id = $benefit->parentable_id;
        $payloadWithArea->area_id = -1;

        $create = $this->actingAs($this->admin, 'api')->json('POST', '/api/partners/benefits', $payload);
        $create
            ->assertStatus(422)
            ->assertJsonStructureExact([
                'message',
                'errors' => [
                    'partner',
                    'contact',
                    'benefit',
                    'division_id'
                ]
            ]);

        $update = $this->actingAs($this->admin, 'api')->json('PUT', '/api/partners/benefits/'.$benefit->id, $payload);
        $update
            ->assertStatus(422)
            ->assertJsonStructureExact([
                'message',
                'errors' => [
                    'partner',
                    'contact',
                    'benefit',
                    'division_id'
                ]
            ]);

        $updateWithArea = $this->actingAs($this->admin, 'api')->json('PUT', '/api/partners/benefits/'.$benefit->id, $payloadWithArea->toArray());
        $updateWithArea
            ->assertStatus(422)
            ->assertJsonStructureExact([
                'message',
                'errors' => [
                    'area_id'
                ]
            ]);
    }

    /** @test */
    public function canCreate() {
       $payload = factory(Benefit::class)->state('request')->make();

       $response = $this->actingAs($this->admin, 'api')->json('POST', '/api/partners/benefits', $payload->toArray());
       $response
        ->assertStatus(201)
        ->assertJsonStructureExact([
            'id',
            'partner',
            'contact',
            'benefit',
            'division' => [
                'id',
                'name',
                'ionicon'
            ],
            'area' => [
                'id',
                'name'
            ]
        ]);

        $justFields = collect($payload);
        $justFields->forget(['division_id', 'area_id']);
        $this->assertDatabaseHas($this->getTable('Benefit'), $justFields->toArray());
    }

    /** @test */
    public function canCreateOrphan() {
        $payload = factory(Benefit::class)->state('request-orphan')->make();

        $response = $this->actingAs($this->admin, 'api')->json('POST', '/api/partners/benefits', $payload->toArray());
        $response
            ->assertStatus(201)
            ->assertJsonStructure([
                'id',
                'partner',
                'contact',
                'benefit',
                'division' => [
                    'id',
                    'name',
                    'ionicon'
                ],
            ]);

        $justFields = collect($payload);
        $justFields->forget(['division_id', 'area_id']);
        $this->assertDatabaseHas($this->getTable('Benefit'), $justFields->toArray());
    }

    /** @test */
    public function canShow() {
        $benefit = factory(Benefit::class)->create();

        $response = $this->actingAs($this->admin, 'api')->json('GET', '/api/partners/benefits/'.$benefit->id);
        $response
            ->assertStatus(200)
            ->assertJsonStructureExact([
                'id',
                'partner',
                'contact',
                'benefit',
                'division' => [
                    'id',
                    'name',
                    'ionicon'
                ],
                'area' => [
                    'id',
                    'name'
                ]
            ]);
    }

    /** @test */
    public function canUpdate() {
        $benefit = factory(Benefit::class)->create();
        $payload = factory(Benefit::class)->state('request')->make();
        $justFields = collect($payload);
        $justFields->forget(['division_id', 'area_id', 'parentable_id', 'parentable_type']);

        $response = $this->actingAs($this->admin, 'api')->json('PUT', '/api/partners/benefits/'.$benefit->id, $payload->toArray());
        $response
            ->assertStatus(200)
            ->assertJsonFragment($justFields->toArray())
            ->assertJsonStructureExact([
                'id',
                'partner',
                'contact',
                'benefit',
                'division' => [
                    'id',
                    'name',
                    'ionicon'
                ],
                'area' => [
                    'id',
                    'name'
                ]
            ]);
    }

    /** @test */
    public function canUpdateOrphan() {
        $benefit = factory(Benefit::class)->state('orphan')->create();
        $payload = factory(Benefit::class)->state('request-orphan')->make();

        $justFields = collect($payload);
        $justFields->forget(['division_id', 'area_id', 'parentable_id', 'parentable_type']);

        $response = $this->actingAs($this->admin, 'api')->json('PUT', '/api/partners/benefits/'.$benefit->id, $payload->toArray());
        $response
        ->assertStatus(200)
        ->assertJsonFragment($justFields->toArray())
        ->assertJsonStructureExact([
            'id',
                'partner',
                'contact',
                'benefit',
                'division' => [
                    'id',
                    'name',
                    'ionicon'
                ],
        ]);
    }

    /** @test */
    public function canDelete() {
        $benefit = factory(Benefit::class)->create();

        $request = $this->actingAs($this->admin, 'api')->json('DELETE', '/api/partners/benefits/'.$benefit->id);
        $request->assertStatus(204);
        $this->assertDatabaseMissing($this->getTable('Benefit'), collect($benefit)->toArray());
    }
}
