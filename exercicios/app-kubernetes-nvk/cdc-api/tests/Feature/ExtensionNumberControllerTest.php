<?php

namespace Tests\Feature;

use App\ExtensionNumber;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExtensionNumberControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function shouldBlockUnauthenticatedAccess() {
        $index = $this->json('GET', '/api/extensions/numbers/1');
        $index->assertStatus(401);

        $update = $this->json('POST', '/api/extensions/numbers');
        $update->assertStatus(401);

        $destroy = $this->json('PUT', '/api/extensions/numbers/-1');
        $destroy->assertStatus(401);

        $destroy = $this->json('DELETE', '/api/extensions/numbers/-1');
        $destroy->assertStatus(401);

        $store = $this->json('GET', '/api/extensions/numbers/all');
        $store->assertStatus(401);
    }

    /** @test */
    public function shouldBlockForbiddenAccess() {
        $number = factory(ExtensionNumber::class)->create();
        $payload = factory(ExtensionNumber::class)->state('request')->make();

        $store = $this->actingAs($this->notAdmin, 'api')->json('POST', '/api/extensions/numbers', $payload->toArray());
        $store->assertStatus(403);

        $update = $this->actingAs($this->notAdmin, 'api')->json('PUT', '/api/extensions/numbers/'.$number->id, $payload->toArray());
        $update->assertStatus(403);

        $destroy = $this->actingAs($this->notAdmin, 'api')->json('DELETE', '/api/extensions/numbers/'.$number->id);
        $destroy->assertStatus(403);
    }

    /** @test */
    public function shouldBlockMethodNotAllowed() {
        $index = $this->json('GET', '/api/extensions/numbers');
        $index->assertStatus(405);
    }

    /** @test */
    public function canReturnListAll() {
        factory(ExtensionNumber::class, 2)->create();

        $response = $this->actingAs($this->admin, 'api')->json('GET', '/api/extensions/numbers/all');
        $response
            ->assertStatus(200)
            ->assertJsonStructureExact([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'number',
                        'division' => [
                            'id',
                            'name',
                            'color'
                        ],
                        'area' => [
                            'id',
                            'name'
                        ]
                    ]
                ]
            ]);
    }

    /** @test */
    public function canReturnListAllOrphans() {
        // orphans have no area
        // parentable == division
        factory(ExtensionNumber::class, 2)->state('orphan')->create();

        $response = $this->actingAs($this->admin, 'api')->json('GET', '/api/extensions/numbers/all');
        $response
            ->assertStatus(200)
            ->assertJsonStructureExact([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'number',
                        'division' => [
                            'id',
                            'name',
                            'color'
                        ]
                    ]
                ]
            ]);
    }

    /** @test */
    public function shouldReturnUnprocessableFields() {
        $number = factory(ExtensionNumber::class)->create();
        $payload = [];
        $payloadWithArea = $number;
        $payloadWithArea->division_id = $number->parentable_id; // by default, number is not saved with 'division_id'
        $payloadWithArea->area_id = -1;

        $create = $this->actingAs($this->admin, 'api')->json('POST', '/api/extensions/numbers', $payload);
        $create
            ->assertStatus(422)
            ->assertJsonStructureExact([
                'message',
                'errors' => [
                    'name',
                    'number',
                    'division_id'
                ]
            ]);

        $update = $this->actingAs($this->admin, 'api')->json('PUT', '/api/extensions/numbers/'.$number->id, $payload);
        $update
            ->assertStatus(422)
            ->assertJsonStructureExact([
                'message',
                'errors' => [
                    'name',
                    'number',
                    'division_id'
                ]
            ]);

        $updateWithArea = $this->actingAs($this->admin, 'api')->json('PUT', '/api/extensions/numbers/'.$number->id, $payloadWithArea->toArray());
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
        $payload = factory(ExtensionNumber::class)->state('request')->make();

        $response = $this->actingAs($this->admin, 'api')->json('POST', '/api/extensions/numbers/', $payload->toArray());
        $response
            ->assertStatus(201)
            ->assertJsonStructureExact([
                'id',
                'name',
                'number',
                'division' => [
                    'id',
                    'name',
                    'color'
                ],
                'area' => [
                    'id',
                    'name'
                ]
            ]);

        $justFields = collect($payload);
        $justFields->forget(['division_id', 'area_id']);
        $this->assertDatabaseHas($this->getTable('ExtensionNumber'), $justFields->toArray());
    }

    /** @test */
    public function canCreateOrphan() {
        $payload = factory(ExtensionNumber::class)->state('request-orphan')->make();

        $response = $this->actingAs($this->admin, 'api')->json('POST', '/api/extensions/numbers/', $payload->toArray());
        $response
            ->assertStatus(201)
            ->assertJsonStructureExact([
                'id',
                'name',
                'number',
                'division' => [
                    'id',
                    'name',
                    'color'
                ]
            ]);

        $justFields = collect($payload);
        $justFields->forget(['division_id', 'area_id']);
        $this->assertDatabaseHas($this->getTable('ExtensionNumber'), $justFields->toArray());
    }

    /** @test */
    public function canShow() {
        $number = factory(ExtensionNumber::class)->create();

        $response = $this->actingAs($this->admin, 'api')->json('GET', '/api/extensions/numbers/'.$number->id);
        $response
            ->assertStatus(200)
            ->assertJsonStructureExact([
                'id',
                'name',
                'number',
                'division' => [
                    'id',
                    'name',
                    'color'
                ],
                'area' => [
                    'id',
                    'name'
                ]
            ]);
    }

    /** @test */
    public function canUpdate() {
        $number = factory(ExtensionNumber::class)->create();
        $payload = factory(ExtensionNumber::class)->state('request')->make();
        $justFields = collect($payload);
        $justFields->forget(['division_id', 'area_id', 'parentable_id', 'parentable_type']);

        $response = $this->actingAs($this->admin, 'api')->json('PUT', '/api/extensions/numbers/'.$number->id, $payload->toArray());
        $response
            ->assertStatus(200)
            ->assertJsonFragment($justFields->toArray())
            ->assertJsonStructureExact([
                'id',
                'name',
                'number',
                'division' => [
                    'id',
                    'name',
                    'color'
                ],
                'area' => [
                    'id',
                    'name'
                ]
            ]);
    }

    /** @test */
    public function canUpdateOrphan() {
        $number = factory(ExtensionNumber::class)->state('orphan')->create();
        $payload = factory(ExtensionNumber::class)->state('request-orphan')->make();
        $justFields = collect($payload);
        $justFields->forget(['division_id', 'area_id', 'parentable_id', 'parentable_type']);

        $response = $this->actingAs($this->admin, 'api')->json('PUT', '/api/extensions/numbers/'.$number->id, $payload->toArray());
        $response
            ->assertStatus(200)
            ->assertJsonFragment($justFields->toArray())
            ->assertJsonStructureExact([
                'id',
                'name',
                'number',
                'division' => [
                    'id',
                    'name',
                    'color'
                ]
            ]);
    }

    /** @test */
    public function canDelete() {
        $number = factory(ExtensionNumber::class)->create();

        $request = $this->actingAs($this->admin, 'api')->json('DELETE', '/api/extensions/numbers/'.$number->id);
        $request->assertStatus(204);
        $this->assertDatabaseMissing($this->getTable('ExtensionNumber'), collect($number)->toArray());
    }
}
