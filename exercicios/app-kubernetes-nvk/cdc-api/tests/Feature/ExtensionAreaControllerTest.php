<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\ExtensionArea;

class ExtensionAreaControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function shouldBlockUnauthenticatedAccess() {
        $all = $this->json('GET', '/api/extensions/areas/all');
        $all->assertStatus(401);

        $show = $this->json('GET', '/api/extensions/areas/1');
        $show->assertStatus(401);

        $store = $this->json('POST', '/api/extensions/areas');
        $store->assertStatus(401);

        $update = $this->json('PUT', '/api/extensions/areas/-1');
        $update->assertStatus(401);

        $destroy = $this->json('DELETE', '/api/extensions/areas/-1');
        $destroy->assertStatus(401);
    }

    /** @test */
    public function shouldBlockForbiddenAccess() {
        $extensionArea = factory(ExtensionArea::class)->create();
        $payload = factory(ExtensionArea::class)->make();

        $store = $this->actingAs($this->notAdmin, 'api')->json('POST', '/api/extensions/areas', $payload->toArray());
        $store->assertStatus(403);

        $update = $this->actingAs($this->notAdmin, 'api')->json('PUT', '/api/extensions/areas/'.$extensionArea->id, $payload->toArray());
        $update->assertStatus(403);

        $destroy = $this->actingAs($this->notAdmin, 'api')->json('DELETE', '/api/extensions/areas/'.$extensionArea->id);
        $destroy->assertStatus(403);
    }

    /** @test */
    public function shouldBlockMethodNotAllowed() {
        $index = $this->json('GET', '/api/extensions/areas');
        $index->assertStatus(405);
    }

    /** @test */
    public function canReturnListAll() {
        factory(ExtensionArea::class, 5)->create();

        $response = $this->actingAs($this->admin, 'api')->json('GET', '/api/extensions/areas/all');
        $response
            ->assertStatus(200)
            ->assertJsonStructureExact([
                'data' => [
                    '*' => [
                        'id',
                        'name',
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
    public function canReturnDivisionless() {
        factory(ExtensionArea::class, 5)->create([
            'extension_division_id' => null
        ]);

        $response = $this->actingAs($this->admin, 'api')->json('GET', '/api/extensions/areas/divisionless');
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
    public function shouldReturnUnprocessableFields() {
        $payload = [];

        // validate name must be required when no areas
        $create = $this->actingAs($this->admin, 'api')->json('POST', '/api/extensions/areas', $payload);
        $create
            ->assertStatus(422)
            ->assertJsonStructureExact([
                'message',
                'errors' => [
                    'name'
                ]
            ]);

        $singleExtensionArea = factory(ExtensionArea::class)->make();

        // valid name required
        $validationErrName = collect($singleExtensionArea->toArray())->forget(['name']);
        $createSingle1 = $this->actingAs($this->admin, 'api')->json('POST', '/api/extensions/areas', $validationErrName->toArray());
        $createSingle1
            ->assertStatus(422)
            ->assertJsonStructureExact([
                'message',
                'errors' => [
                    'name'
                ]
            ]);

        // validate integer (when passed)
        $validationErrExtId = collect($singleExtensionArea->toArray())->merge(['extension_division_id' => 'text to fail']);
        $createSingle2 = $this->actingAs($this->admin, 'api')->json('POST', '/api/extensions/areas', $validationErrExtId->toArray());
        $createSingle2
            ->assertStatus(422)
            ->assertJsonStructureExact([
                'message',
                'errors' => [
                    'extension_division_id'
                ]
            ]);

        $extensionArea = factory(ExtensionArea::class)->create();
        $payloadError = collect($extensionArea)->forget('name')->merge(['extension_division_id' => 'text to fail']);
        $update = $this->actingAs($this->admin, 'api')->json('PUT', '/api/extensions/areas/'.$extensionArea->id, $payloadError->toArray());
        $update
            ->assertStatus(422)
            ->assertJsonStructureExact([
                'message',
                'errors' => [
                    'name',
                    'extension_division_id'
                ]
            ]);
    }

    /** @test */
    public function shouldReturnUnprocessableFieldsBulk() {
        $bulkExtensionArea = factory(ExtensionArea::class, 2)->make()->each(function($area) {
            $area->name = '';
            $area->extension_division_id = 'text to fail';
        });
        $bulkPayloadError = [
            'areas' => $bulkExtensionArea->toArray()
        ];

        $createMulti = $this->actingAs($this->admin, 'api')->json('POST', '/api/bulk/extensions/areas', $bulkPayloadError);
        $createMulti
            ->assertStatus(422)
            ->assertJsonStructureExact([
                'message',
                'errors' => [
                    'areas.0.name',
                    'areas.0.extension_division_id',
                    'areas.1.name',
                    'areas.1.extension_division_id',
                ]
            ]);
    }

    /** @test */
    public function canCreate() {
        $payload = factory(ExtensionArea::class)->make();

        $request = $this->actingAs($this->admin, 'api')->json('POST', '/api/extensions/areas/', $payload->toArray());
        $request
            ->assertStatus(201)
            ->assertJsonStructureExact([
                'id',
                'name',
                'division' => [
                    'id',
                    'name',
                    'color'
                ]
            ]);
        $this->assertDatabaseHas($this->getTable('ExtensionArea'), $payload->toArray());
    }

    /** @test */
    public function canCreateWithoutDivision() {
        $extensionArea = factory(ExtensionArea::class)->make([
            'extension_division_id' => ''
        ]);
        $payload = $extensionArea->toArray();

        $request = $this->actingAs($this->admin, 'api')->json('POST', '/api/extensions/areas/', $payload);
        $request
            ->dump()
            ->assertStatus(201)
            ->assertJsonStructureExact([
                'id',
                'name',
                'division'
            ]);
        $dbAssert = collect($payload)->forget('extension_division_id');
        $this->assertDatabaseHas($this->getTable('ExtensionArea'), $dbAssert->toArray());
    }

    /** @test */
    public function canCreateBulk() {
        $bulkExtensionArea = factory(ExtensionArea::class, 2)->make();
        $payload = [
            'areas' => $bulkExtensionArea->toArray()
        ];

        $request = $this->actingAs($this->admin, 'api')->json('POST', '/api/bulk/extensions/areas/', $payload);
        $request
            ->assertStatus(201)
            ->assertJsonStructureExact([
                '*' => [
                    'id',
                    'name',
                    'division' => [
                        'id',
                        'name',
                        'color'
                    ]
                ]
            ]);

        $newAreas = collect($request->decodeResponseJson())->map(function($area) {
            return collect($area)->forget('division');
        })->toArray();

        $this->assertCount(2, $newAreas);

        foreach ($newAreas as $newArea) {
            $this->assertDatabaseHas($this->getTable('ExtensionArea'), $newArea);
        }
    }

    /** @test */
    public function canCreateBulkWithoutDivision() {
        $bulkExtensionAreaDivisionless = factory(ExtensionArea::class, 2)->make()->each(function($area) {
            $area->extension_division_id = '';
        });
        $payload = [
            'areas' => $bulkExtensionAreaDivisionless->toArray()
        ];

        $request = $this->actingAs($this->admin, 'api')->json('POST', '/api/bulk/extensions/areas/', $payload);
        $request
            ->assertStatus(201)
            ->dump()
            ->assertJsonStructureExact([
                '*' => [
                    'id',
                    'name',
                    'division'
                ]
            ]);

        $newAreas = collect($request->decodeResponseJson())->map(function($area) {
            return collect($area)->forget('division');
        })->toArray();

        $this->assertCount(2, $newAreas);

        foreach ($newAreas as $newArea) {
            $this->assertDatabaseHas($this->getTable('ExtensionArea'), $newArea);
        }
    }

    /** @test */
    public function canShow() {
        $extensionArea = factory(ExtensionArea::class)->create();

        $response = $this->actingAs($this->admin, 'api')->json('GET', '/api/extensions/areas/'.$extensionArea->id);
        $response
            ->assertStatus(200)
            ->assertJsonStructureExact([
                'id',
                'name',
                'division' => [
                    'id',
                    'name',
                    'color'
                ]
            ]);
    }

    /** @test */
    public function canUpdate() {
        $extensionArea = factory(ExtensionArea::class)->create();
        $payload = factory(ExtensionArea::class)->make();
        $justFields = collect($payload)->forget('extension_division_id');

        $response = $this->actingAs($this->admin, 'api')->json('PUT', '/api/extensions/areas/'.$extensionArea->id, $payload->toArray());
        $response
            ->assertStatus(200)
            ->assertJsonFragment($justFields->toArray())
            ->assertJsonStructureExact([
                'id',
                'name',
                'division' => [
                    'id',
                    'name',
                    'color'
                ]
            ]);

        $this->assertDatabaseHas($this->getTable('ExtensionArea'), $justFields->toArray());
    }

    /** @test */
    public function canDelete() {
        $extensionArea = factory(ExtensionArea::class)->create();

        $request = $this->actingAs($this->admin, 'api')->json('DELETE', '/api/extensions/areas/'.$extensionArea->id);
        $request->assertStatus(204);

        $this->assertDatabaseMissing($this->getTable('ExtensionArea'), collect($extensionArea)->toArray());
    }

    /** @test */
    public function canPreventDeleteIfNumbers() {
        $extensionArea = factory(ExtensionArea::class)->state('withNumbers')->create();

        $request = $this->actingAs($this->admin, 'api')->json('DELETE', '/api/extensions/areas/'.$extensionArea->id);
        $request
            ->assertStatus(409)
            ->assertJsonStructureExact([
                'error' => [
                    'description',
                    'message'
                ]
            ]);

        $this->assertDatabaseHas($this->getTable('ExtensionArea'), collect($extensionArea)->toArray());
    }
}
