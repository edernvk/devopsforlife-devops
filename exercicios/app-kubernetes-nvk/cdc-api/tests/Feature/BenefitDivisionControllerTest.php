<?php

namespace Tests\Feature;

use App\BenefitArea;
use App\BenefitDivision;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BenefitDivisionControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function shouldBlockUnauthenticatedAccess() {
        $all = $this->json('GET', '/api/partners/divisions/all');
        $all->assertStatus(401);

        $show = $this->json('GET', '/api/partners/divisions/1');
        $show->assertStatus(401);

        $store = $this->json('POST', '/api/partners/divisions');
        $store->assertStatus(401);

        $update = $this->json('PUT', '/api/partners/divisions/-1');
        $update->assertStatus(401);

        $destroy = $this->json('DELETE', '/api/partners/divisions/-1');
        $destroy->assertStatus(401);
    }

    /** @test */
    public function shouldBlockForbiddenAccess() {
        $benefitDivision = factory(BenefitDivision::class)->create();
        $payload = factory(BenefitDivision::class)->make();

        $store = $this->actingAs($this->notAdmin, 'api')->json('POST', '/api/partners/divisions', $payload->toArray());
        $store->assertStatus(403);

        $update = $this->actingAs($this->notAdmin, 'api')->json('PUT', '/api/partners/divisions/'.$benefitDivision->id, $payload->toArray());
        $update->assertStatus(403);

        $destroy = $this->actingAs($this->notAdmin, 'api')->json('DELETE', '/api/partners/divisions/'.$benefitDivision->id);
        $destroy->assertStatus(403);
    }

    /** @test */
    public function shouldReturnMethodNotAllowed() {
        $index = $this->json('GET', '/api/partners/divisions');
        $index->assertStatus(405);
    }

    /** @test */
    public function canReturnListDivisions() {
        factory(BenefitDivision::class, 5)->create();

        $response = $this->actingAs($this->admin, 'api')->json('GET', '/api/partners/divisions/all');
        $response
            ->assertStatus(200)
            ->assertJsonStructureExact([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'ionicon',
                        'areas' => [
                            '*' => [
                                'id',
                                'name'
                            ]
                        ]
                    ]
                ]
            ]);
    }

    /** @test */
    public function canReturnListAreas() {
        $division = factory(BenefitDivision::class)->state('withAreas')->create();

        $response = $this->actingAs($this->admin, 'api')->json('GET', '/api/partners/divisions/'.$division->id.'/areas');
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
    public function canReturnDivisionsListWithAreasAndBenefitsAndOrphanBenefits() {
        factory(BenefitDivision::class)
            ->states(['withOrphanBenefits', 'withBenefits'])
            ->create();
            
        // factory(BenefitDivision::class)
        //     ->states(['withOrphanBenefits'])
        //     ->create();

        $response = $this->actingAs($this->notAdmin, 'api')->json('GET', '/api/partners/divisions/benefits');
        $response
            ->assertStatus(200)
            ->assertJsonStructureExact([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'ionicon',
                        'areas' => [
                            '*' => [
                                'id',
                                'name',
                                'benefits' => [
                                    '*' => [
                                        'id',
                                        'partner',
                                        'contact',
                                        'benefit'
                                    ]
                                ]
                            ]
                        ],
                        'orphan_benefits' => [
                            '*' => [
                                'id',
                                'partner',
                                'contact',
                                'benefit'
                            ]
                        ]
                    ]
                ]
            ]);
    }

    /** @test */
    public function shouldReturnUnprocessableFields() {
        $benefitDivision = factory(BenefitDivision::class)->create();
        $payload = [];
        $payloadWithValidationError = $benefitDivision;
        $payloadWithValidationError->name = '';
        // `ionicons` field as nullable should pass

        $create = $this->actingAs($this->admin, 'api')->json('POST', '/api/partners/divisions', $payload);
        $create
            ->assertStatus(422)
            ->assertJsonStructureExact([
                'message',
                'errors' => [
                    'name'
                ]
            ]);

        $update = $this->actingAs($this->admin, 'api')->json('PUT', '/api/partners/divisions/'.$benefitDivision->id, $payloadWithValidationError->toArray());
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
        $payload = factory(BenefitDivision::class)->make();

        $request = $this->actingAs($this->admin, 'api')->json('POST', '/api/partners/divisions/', $payload->toArray());
        $request
            ->assertStatus(201)
            ->assertJsonStructureExact([
                'id',
                'name',
                'ionicon',
                'areas'
            ]);
        $this->assertDatabaseHas($this->getTable('BenefitDivision'), $payload->toArray());
    }

    /** @test */
    public function canCreateWithAreas() {
        $payload = factory(BenefitDivision::class)->state('request-areas')->make();

        $request = $this->actingAs($this->admin, 'api')->json('POST', '/api/partners/divisions/', $payload->toArray());
        $request
            ->assertStatus(201)
            ->assertJsonStructureExact([
                'id',
                'name',
                'ionicon',
                'areas' => [
                    '*' => [
                        'id',
                        'name'
                    ]
                ]
            ]);
        $check = collect($payload)->forget('areas');
        $this->assertDatabaseHas($this->getTable('BenefitDivision'), $check->toArray());
    }

    /** @test */
    public function canShow() {
        $benefitDivision = factory(BenefitDivision::class)->create();

        $response = $this->actingAs($this->admin, 'api')->json('GET', '/api/partners/divisions/'.$benefitDivision->id);
        $response
            ->assertStatus(200)
            ->assertJsonStructureExact([
                'id',
                'name',
                'ionicon',
                'areas'
            ]);
    }

    /** @test */
    public function canUpdateWithoutAreas() {
        $benefitDivision = factory(BenefitDivision::class)->create();
        $payload = factory(BenefitDivision::class)->make();
        $justFields = collect($payload);

        $response = $this->actingAs($this->admin, 'api')->json('PUT', '/api/partners/divisions/'.$benefitDivision->id, $payload->toArray());
        $response
            ->assertStatus(200)
            ->assertJsonFragment($justFields->toArray())
            ->assertJsonStructureExact([
                'id',
                'name',
                'ionicon',
                'areas'
            ]);

        $this->assertDatabaseHas($this->getTable('BenefitDivision'), $justFields->toArray());
    }

    /** @test */
    public function canUpdateWithNewAreas() {
        $benefitDivision = factory(BenefitDivision::class)->state('withAreas')->create();
        $payload = factory(BenefitDivision::class)->make();
        $justFields = collect($payload);
        $check = $justFields->forget('areas');

        $newAreas = factory(BenefitArea::class, 2)->create([
            'benefit_division_id' => null
        ]);

        $payloadWithNewAreas = $justFields->merge([ 'areas' => $newAreas->toArray() ]);

        $response = $this->actingAs($this->admin, 'api')->json('PUT', '/api/partners/divisions/'.$benefitDivision->id, $payloadWithNewAreas->toArray());
        $response
            ->assertStatus(200)
            ->assertJsonFragment($check->toArray())
            ->assertJsonStructureExact([
                'id',
                'name',
                'ionicon',
                'areas' => [
                    '*' => [
                        'id',
                        'name'
                    ]
                ]
            ]);

        $this->assertDatabaseHas($this->getTable('BenefitDivision'), $check->toArray());

        $updatedAreas = collect($response->decodeResponseJson()['areas'])->map(function ($res) use ($benefitDivision) {
            return collect($res)->merge(['benefit_division_id' => $benefitDivision->id]);
        })->toArray();

        $this->assertCount(2, $updatedAreas);

        foreach ($updatedAreas as $updated) {
            $this->assertDatabaseHas($this->getTable('BenefitArea'),$updated);
        }
    }

    /** @test */
    public function canUpdateWithAddedAreas() {
        $benefitDivision = factory(BenefitDivision::class)->state('withAreas')->create();
        $payload = factory(BenefitDivision::class)->make();
        $justFields = collect($payload);
        $check = $justFields->forget('areas');

        $newAreas = factory(BenefitArea::class, 2)->create([
            'benefit_division_id' => null
        ]);

        $payloadWithAddedAreas = $justFields->merge([
            'areas' => $benefitDivision->areas->merge($newAreas)->toArray()
        ]);

        $response = $this->actingAs($this->admin, 'api')->json('PUT', '/api/partners/divisions/'.$benefitDivision->id, $payloadWithAddedAreas->toArray());
        $response
            ->assertStatus(200)
            ->assertJsonFragment($check->toArray())
            ->assertJsonStructureExact([
                'id',
                'name',
                'ionicon',
                'areas' => [
                    '*' => [
                        'id',
                        'name'
                    ]
                ]
            ]);

        $this->assertDatabaseHas($this->getTable('BenefitDivision'), $check->toArray());

        $updatedAreas = collect($response->decodeResponseJson()['areas'])->map(function ($res) use ($benefitDivision) {
            return collect($res)->merge(['benefit_division_id' => $benefitDivision->id])->toArray();
        })->toArray();

        $this->assertCount(3, $updatedAreas);

        foreach ($updatedAreas as $updated) {
            $this->assertDatabaseHas($this->getTable('BenefitArea'), $updated);
        }
    }

    /** @test */
    public function canUpdateWithNoAreas() {
        $benefitDivision = factory(BenefitDivision::class)->state('withAreas')->create();
        $payload = factory(BenefitDivision::class)->make();
        $justFields = collect($payload);
        $check = $justFields->forget('areas');

        $payloadWithNoAreas = $justFields->merge([
            'areas' => []
        ]);

        $response = $this->actingAs($this->admin, 'api')->json('PUT', '/api/partners/divisions/'.$benefitDivision->id, $payloadWithNoAreas->toArray());
        $response
            ->assertStatus(200)
            ->assertJsonFragment($check->toArray())
            ->assertJsonStructureExact([
                'id',
                'name',
                'ionicon',
                'areas'
            ]);

            $this->assertDatabaseHas($this->getTable('BenefitDivision'), $check->toArray());

            $updatedAreas = $response->decodeResponseJson()['areas'];
            $this->assertCount(0, $updatedAreas);
        }

    /** @test */
    public function canDelete() {
        $benefitDivision = factory(BenefitDivision::class)->create();

        $request = $this->actingAs($this->admin, 'api')->json('DELETE', '/api/partners/divisions/'.$benefitDivision->id);
        $request->assertStatus(204);
        $this->assertDatabaseMissing($this->getTable('BenefitDivision'), collect($benefitDivision)->toArray());
    }

    /** @test */
    public function canPreventDeleteIfDepedents() {
        $benefitDivision = factory(BenefitDivision::class)->states(['withBenefits','withOrphanBenefits'])->create();

        $request = $this->actingAs($this->admin, 'api')->json('DELETE', '/api/partners/divisions/'.$benefitDivision->id);
        $request
            ->assertStatus(409)
            ->assertJsonStructureExact([
                'error' => [
                    'description',
                    'message'
                ]
            ]);

        $this->assertDatabaseHas($this->getTable('BenefitDivision'), collect($benefitDivision)->toArray());
    }
}
