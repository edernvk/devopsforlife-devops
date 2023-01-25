<?php

namespace Tests\Feature;

use App\ExtensionArea;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\ExtensionDivision;

class ExtensionDivisionControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function shouldBlockUnauthenticatedAccess() {
        $all = $this->json('GET', '/api/extensions/divisions/all');
        $all->assertStatus(401);

        $show = $this->json('GET', '/api/extensions/divisions/1');
        $show->assertStatus(401);

        $store = $this->json('POST', '/api/extensions/divisions');
        $store->assertStatus(401);

        $update = $this->json('PUT', '/api/extensions/divisions/-1');
        $update->assertStatus(401);

        $destroy = $this->json('DELETE', '/api/extensions/divisions/-1');
        $destroy->assertStatus(401);
    }

    /** @test */
    public function shouldBlockForbiddenAccess() {
        $extensionDivision = factory(ExtensionDivision::class)->create();
        $payload = factory(ExtensionDivision::class)->make();

        $store = $this->actingAs($this->notAdmin, 'api')->json('POST', '/api/extensions/divisions', $payload->toArray());
        $store->assertStatus(403);

        $update = $this->actingAs($this->notAdmin, 'api')->json('PUT', '/api/extensions/divisions/'.$extensionDivision->id, $payload->toArray());
        $update->assertStatus(403);

        $destroy = $this->actingAs($this->notAdmin, 'api')->json('DELETE', '/api/extensions/divisions/'.$extensionDivision->id);
        $destroy->assertStatus(403);
    }

    /** @test */
    public function shouldReturnMethodNotAllowed() {
        $index = $this->json('GET', '/api/extensions/divisions');
        $index->assertStatus(405);
    }

    /** @test */
    public function canReturnListDivisions() {
        factory(ExtensionDivision::class, 5)->create();

        $response = $this->actingAs($this->admin, 'api')->json('GET', '/api/extensions/divisions/all');
        $response
            ->assertStatus(200)
            ->assertJsonStructureExact([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'color'
                    ]
                ]
            ]);
    }

    /** @test */
    public function canReturnListAreas() {
        $division = factory(ExtensionDivision::class)->state('withAreas')->create();

        $response = $this->actingAs($this->admin, 'api')->json('GET', '/api/extensions/divisions/'.$division->id.'/areas');
        $response
            ->assertStatus(200)
            ->assertJsonStructureExact([
                'data' => [
                    '*' => [
                        'id',
                        'name'
                    ]
                ]
            ]);
    }

    /** @test */
    public function canReturnDivisionsListWithAreasAndNumbersAndOrphanNumbers() {
        factory(ExtensionDivision::class)
            ->states(['withNumbers', 'withOrphanNumbers'])
            ->create();

        $response = $this->actingAs($this->notAdmin, 'api')->json('GET', '/api/extensions/divisions/numbers');
        $response
            ->assertStatus(200)
            ->assertJsonStructureExact([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'color',
                        'orphan_numbers' => [
                            '*' => [
                                'id',
                                'name',
                                'number'
                            ]
                        ],
                        'areas' => [
                            '*' => [
                                'id',
                                'name',
                                'numbers' => [
                                    '*' => [
                                        'id',
                                        'name',
                                        'number'
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]);
    }

    /** @test */
    public function shouldReturnUnprocessableFields() {
        $extensionDivision = factory(ExtensionDivision::class)->create();
        $payload = [];
        $payloadWithValidationError = $extensionDivision;
        $payloadWithValidationError->name = '';
        // `color` field as nullable should pass

        $create = $this->actingAs($this->admin, 'api')->json('POST', '/api/extensions/divisions', $payload);
        $create
            ->assertStatus(422)
            ->assertJsonStructureExact([
                'message',
                'errors' => [
                    'name'
                ]
            ]);

        $update = $this->actingAs($this->admin, 'api')->json('PUT', '/api/extensions/divisions/'.$extensionDivision->id, $payloadWithValidationError->toArray());
        $update
            ->assertStatus(422)
            ->assertJsonStructureExact([
                'message',
                'errors' => [
                    'name'
                ]
            ]);
    }

    /** @test */
    public function canCreateWithoutAreas() {
        $payload = factory(ExtensionDivision::class)->make();

        $request = $this->actingAs($this->admin, 'api')->json('POST', '/api/extensions/divisions/', $payload->toArray());
        $request
            ->assertStatus(201)
            ->assertJsonStructureExact([
                'id',
                'name',
                'color',
                'areas'
            ]);
        $this->assertDatabaseHas($this->getTable('ExtensionDivision'), $payload->toArray());
    }

    /** @test */
    public function canCreateWithAreas() {
        $payload = factory(ExtensionDivision::class)->state('request-areas')->make();

        $request = $this->actingAs($this->admin, 'api')->json('POST', '/api/extensions/divisions/', $payload->toArray());
        $request
            ->assertStatus(201)
            ->assertJsonStructureExact([
                'id',
                'name',
                'color',
                'areas' => [
                    '*' => [
                        'id',
                        'name'
                    ]
                ]
            ]);

        $check = collect($payload)->forget('areas');
        $this->assertDatabaseHas($this->getTable('ExtensionDivision'), $check->toArray());
    }

    /** @test */
    public function canShow() {
        $division = factory(ExtensionDivision::class)->create();

        $response = $this->actingAs($this->admin, 'api')->json('GET', '/api/extensions/divisions/'.$division->id);
        $response
            ->dump()
            ->assertStatus(200)
            ->assertJsonStructureExact([
                'id',
                'name',
                'color',
                'areas'
            ]);
    }

    /** @test */
    public function canUpdateWithoutAreas() {
        $extensionDivision = factory(ExtensionDivision::class)->create();
        $payload = factory(ExtensionDivision::class)->make();
        $justFields = collect($payload);

        $response = $this->actingAs($this->admin, 'api')->json('PUT', '/api/extensions/divisions/'.$extensionDivision->id, $payload->toArray());
        $response
            ->assertStatus(200)
            ->assertJsonFragment($justFields->toArray())
            ->assertJsonStructureExact([
                'id',
                'name',
                'color',
                'areas'
            ]);

        $this->assertDatabaseHas($this->getTable('ExtensionDivision'), $justFields->toArray());
    }

    /** @test */
    public function canUpdateWithNewAreas() {
        $extensionDivision = factory(ExtensionDivision::class)->state('withAreas')->create();
        $payload = factory(ExtensionDivision::class)->make();
        $justFields = collect($payload);
        $check = $justFields->forget('areas');

        $newAreas = factory(ExtensionArea::class, 2)->create([
            'extension_division_id' => null // would be returned as "available" areas
        ]);
        $payloadWithNewAreas = $justFields->merge(["areas" => $newAreas->toArray()]);

        $response = $this->actingAs($this->admin, 'api')->json('PUT', '/api/extensions/divisions/'.$extensionDivision->id, $payloadWithNewAreas->toArray());
        $response
            ->assertStatus(200)
            ->assertJsonFragment($check->toArray())
            ->assertJsonStructureExact([
                'id',
                'name',
                'color',
                'areas' => [
                    '*' => [
                        'id',
                        'name'
                    ]
                ]
            ]);

        $this->assertDatabaseHas($this->getTable('ExtensionDivision'), $check->toArray());

        $updatedAreas = collect($response->decodeResponseJson()['areas'])->map(function($r) use ($extensionDivision) {
            return collect($r)->merge(['extension_division_id' => $extensionDivision->id])->toArray();
        })->toArray();

        $this->assertCount(2, $updatedAreas);

        foreach ($updatedAreas as $updated) {
            $this->assertDatabaseHas($this->getTable('ExtensionArea'), $updated);
        }
    }

    /** @test */
    public function canUpdateWithAddedAreas() {
        $extensionDivision = factory(ExtensionDivision::class)->state('withAreas')->create();
        $payload = factory(ExtensionDivision::class)->make();
        $justFields = collect($payload);
        $check = $justFields->forget('areas');

        $newAreas = factory(ExtensionArea::class, 2)->create([
            'extension_division_id' => null // would be returned as "available" areas
        ]);

        $payloadWithAddedAreas = $justFields->merge([
            'areas' => $extensionDivision->areas->merge($newAreas)->toArray()
        ]);

        $response = $this->actingAs($this->admin, 'api')->json('PUT', '/api/extensions/divisions/'.$extensionDivision->id, $payloadWithAddedAreas->toArray());
        $response
            ->assertStatus(200)
            ->assertJsonFragment($check->toArray())
            ->assertJsonStructureExact([
                'id',
                'name',
                'color',
                'areas' => [
                    '*' => [
                        'id',
                        'name'
                    ]
                ]
            ]);

        $this->assertDatabaseHas($this->getTable('ExtensionDivision'), $check->toArray());

        $updatedAreas = collect($response->decodeResponseJson()['areas'])->map(function($r) use ($extensionDivision) {
            return collect($r)->merge(['extension_division_id' => $extensionDivision->id])->toArray();
        })->toArray();

        $this->assertCount(3, $updatedAreas);

        foreach ($updatedAreas as $updated) {
            $this->assertDatabaseHas($this->getTable('ExtensionArea'), $updated);
        }
    }

    /** @test */
    public function canUpdateWithNoAreas() {
        $extensionDivision = factory(ExtensionDivision::class)->state('withAreas')->create();
        $payload = factory(ExtensionDivision::class)->make();
        $justFields = collect($payload);
        $check = $justFields->forget('areas');

        $payloadWithNoAreas = $justFields->merge([
            'areas' => []
        ]);

        $response = $this->actingAs($this->admin, 'api')->json('PUT', '/api/extensions/divisions/'.$extensionDivision->id, $payloadWithNoAreas->toArray());
        $response
            ->assertStatus(200)
            ->assertJsonFragment($check->toArray())
            ->assertJsonStructureExact([
                'id',
                'name',
                'color',
                'areas'
            ]);

        $this->assertDatabaseHas($this->getTable('ExtensionDivision'), $check->toArray());

        $updatedAreas = $response->decodeResponseJson()['areas'];
        $this->assertCount(0, $updatedAreas);
    }

    /** @test */
    public function canDelete() {
        $extensionDivision = factory(ExtensionDivision::class)->create();

        $request = $this->actingAs($this->admin, 'api')->json('DELETE', '/api/extensions/divisions/'.$extensionDivision->id);
        $request->assertStatus(204);
        $this->assertDatabaseMissing($this->getTable('ExtensionDivision'), collect($extensionDivision)->toArray());
    }

    /** @test */
    public function canPreventDeleteIfDependents() {
        $extensionDivision = factory(ExtensionDivision::class)->states(['withNumbers', 'withOrphanNumbers'])->create();

        $request = $this->actingAs($this->admin, 'api')->json('DELETE', '/api/extensions/divisions/'.$extensionDivision->id);
        $request
            ->assertStatus(409)
            ->assertJsonStructureExact([
                'error' => [
                    'description',
                    'message'
                ]
            ]);

        $this->assertDatabaseHas($this->getTable('ExtensionDivision'), collect($extensionDivision)->toArray());
    }
}
