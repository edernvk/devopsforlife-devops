<?php

namespace Tests\Feature;

use App\BenefitArea;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BenefitAreaControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function shouldBlockUnauthenticatedAccess() {
        $all = $this->json('GET', '/api/partners/areas/all');
        $all->assertStatus(401);

        $show = $this->json('GET', '/api/partners/areas/1');
        $show->assertStatus(401);

        $store = $this->json('POST', '/api/partners/areas');
        $store->assertStatus(401);

        $update = $this->json('PUT', '/api/partners/areas/-1');
        $update->assertStatus(401);

        $destroy = $this->json('DELETE', '/api/partners/areas/-1');
        $destroy->assertStatus(401);
    }

    /** @test */
    public function shouldBlockForbiddenAccess() {
        $benefitArea = factory(BenefitArea::class)->create();
        $payload = factory(BenefitArea::class)->make();

        $store = $this->actingAs($this->notAdmin, 'api')->json('POST', '/api/partners/areas', $payload->toArray());
        $store->assertStatus(403);

        $update = $this->actingAs($this->notAdmin, 'api')->json('PUT', '/api/partners/areas/'.$benefitArea->id, $payload->toArray());
        $update->assertStatus(403);

        $destroy = $this->actingAs($this->notAdmin, 'api')->json('DELETE', '/api/partners/areas/'.$benefitArea->id);
        $destroy->assertStatus(403);
    }

    /** @test */
    public function shouldBlockMethodNotAllowed() {
        $index = $this->json('GET', '/api/partners/areas');
        $index->assertStatus(405);
    }

    /** @test */
    public function canReturnListAll() {
        factory(BenefitArea::class, 5)->create();

        $response = $this->actingAs($this->admin, 'api')->json('GET', '/api/partners/areas/all');
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
                            'ionicon'
                        ]
                    ]
                ]
            ]);
    }

    /** @test */
    public function canReturnDivisionless() {
        factory(BenefitArea::class, 5)->create([
            'benefit_division_id' => null
        ]);

        $response = $this->actingAs($this->admin, 'api')->json('GET', '/api/partners/areas/divisionless');
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

        $create = $this->actingAs($this->admin, 'api')->json('POST', 'api/partners/areas', $payload);
        $create
            ->assertStatus(422)
            ->assertJsonStructureExact([
                'message',
                'errors' => [
                    'name'
                ]
            ]);

        $singleBenefitArea = factory(BenefitArea::class)->make();
        
        // valid name required
        $validationErrName = collect($singleBenefitArea->toArray())->forget(['name']);
        $createSingle1 = $this->actingAs($this->admin, 'api')->json('POST', '/api/partners/areas', $validationErrName->toArray());
        $createSingle1
            ->assertStatus(422)
            ->assertJsonStructureExact([
                'message',
                'errors' => [
                    'name'
                ]
            ]);

        $validationErrBenId = collect($singleBenefitArea->toArray())->merge(['benefit_division_id' => 'text to fail']);
        $createSingle2 = $this->actingAs($this->admin, 'api')->json('POST', '/api/partners/areas', $validationErrBenId->toArray());
        $createSingle2
            ->assertStatus(422)
            ->assertJsonStructureExact([
                'message',
                'errors' => [
                    'benefit_division_id'
                ]
            ]);

        $benefitArea = factory(BenefitArea::class)->create();
        $payloadError = collect($benefitArea)->forget('name')->merge(['benefit_division_id' => 'text to fail']);
        $update = $this->actingAs($this->admin, 'api')->json('PUT', '/api/partners/areas/'. $benefitArea->id, $payloadError->toArray());
        $update
            ->assertStatus(422)
            ->assertJsonStructureExact([
                'message',
                'errors' => [
                    'name',
                    'benefit_division_id'
                ]
            ]);
    }

    /** @test */
    public function shouldReturnUnprocessableFieldsBulk() {
        $bulkBenefitArea = factory(BenefitArea::class, 2)->make()->each(function ($area) {
            $area->name = '';
            $area->benefit_division_id = 'text to fail';
        });      
        $bulkPayloadError = [
            'areas' => $bulkBenefitArea->toArray()
        ];

        $createMulti = $this->actingAs($this->admin, 'api')->json('POST', '/api/bulk/partners/areas', $bulkPayloadError);
        $createMulti
            ->assertStatus(422)
            ->assertJsonStructureExact([
                'message',
                'errors' => [
                    'areas.0.name',
                    'areas.0.benefit_division_id',
                    'areas.1.name',
                    'areas.1.benefit_division_id',
                ]
            ]);
    }

    /** @test */
    public function canCreate() {
        $payload = factory(BenefitArea::class)->make();

        $request = $this->actingAs($this->admin, 'api')->json('POST', '/api/partners/areas/', $payload->toArray());
        $request
            ->assertStatus(201)
            ->assertJsonStructureExact([
                'id',
                'name',
                'division' => [
                    'id',
                    'name',
                    'ionicon'
                ]
            ]);
        $this->assertDatabaseHas($this->getTable('BenefitArea'), $payload->toArray());
    }

    /** @test */
    public function canCreateWithoutDivision() { 
        $benefitArea = factory(BenefitArea::class)->make([
            'benefit_division_id' => ''
        ]);
        $payload = $benefitArea->toArray();

        $request = $this->actingAs($this->admin, 'api')->json('POST', '/api/partners/areas', $payload);
        $request
            ->assertStatus(201)
            ->assertJsonStructureExact([
                'id',
                'name',
                'division'
            ]);
        
        $dbAssert = collect($payload)->forget('benefit_division_id');
        $this->assertDatabaseHas($this->getTable('BenefitArea'), $dbAssert->toArray());
    }

    /** @test */
    public function canCreateBulk() {
        $bulkBenefitArea = factory(BenefitArea::class, 2)->make();
        $payload = [
            'areas' => $bulkBenefitArea->toArray()
        ];

        $request = $this->actingAs($this->admin, 'api')->json('POST', '/api/bulk/partners/areas', $payload);
        $request
            ->assertStatus(201)
            ->assertJsonStructureExact([
                '*' => [
                    'id',
                    'name',
                    'division' => [
                        'id',
                        'name',
                        'ionicon'
                    ]
                ]   
            ]);
        
        $newAreas = collect($request->decodeResponseJson())->map(function ($area) {
            return collect($area)->forget('division');
        })->toArray();

        $this->assertCount(2, $newAreas);

        foreach ($newAreas as $newArea) {
            $this->assertDatabaseHas($this->getTable('BenefitArea'), $newArea);
        }
    }

    /** @test */
    public function canCreateBulkWithoutDivision() {
        $bulkBenefitAreaDivisionless = factory(BenefitArea::class, 2)->make()->each(function ($area) {
            $area->benefit_division_id = '';
        });
        $payload = [
            'areas' => $bulkBenefitAreaDivisionless->toArray()
        ];

        $request = $this->actingAs($this->admin, 'api')->json('POST', '/api/bulk/partners/areas', $payload);
        $request
            ->assertStatus(201)
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
                $this->assertDatabaseHas($this->getTable('BenefitArea'), $newArea);
            }
    }

    /** @test */
    public function canShow() {
        $benefitArea = factory(BenefitArea::class)->create();

        $response = $this->actingAs($this->admin, 'api')->json('GET', '/api/partners/areas/'.$benefitArea->id);
        $response
            ->assertStatus(200)
            ->assertJsonStructureExact([
                'id',
                'name',
                'division' => [
                    'id',
                    'name',
                    'ionicon'
                ]
            ]);
    }

    /** @test */
    public function canUpdate() {
        $benefitArea = factory(BenefitArea::class)->create();
        $payload = factory(BenefitArea::class)->make();
        $justFields = collect($payload)->forget('benefit_division_id');

        $response = $this->actingAs($this->admin, 'api')->json('PUT', '/api/partners/areas/'.$benefitArea->id, $payload->toArray());
        $response
            ->assertStatus(200)
            ->assertJsonFragment($justFields->toArray())
            ->assertJsonStructureExact([
                'id',
                'name',
                'division' => [
                    'id',
                    'name',
                    'ionicon'
                ]
            ]);
        $this->assertDatabaseHas($this->getTable('BenefitArea'), $justFields->toArray());
    }

    /** @test */
    public function canDelete() {
        $benefitArea = factory(BenefitArea::class)->create();
    
        $request = $this->actingAs($this->admin, 'api')->json('DELETE', '/api/partners/areas/'.$benefitArea->id);
        $request->assertStatus(204);
        $this->assertDatabaseMissing($this->getTable('BenefitArea'), collect($benefitArea)->toArray());
    }

    /** @test */
    public function canPreventDeleteIfBenefit() {
        $benefitArea = factory(BenefitArea::class)->state('withBenefits')->create();

        $request = $this->actingAs($this->admin, 'api')->json('DELETE', '/api/partners/areas/'.$benefitArea->id);
        $request
            ->assertStatus(409)
            ->assertJsonStructureExact([
                'error' => [
                    'description',
                    'message'
                ]
            ]);

        $this->assertDatabaseHas($this->getTable('BenefitArea'), collect($benefitArea)->toArray());
    }
}
