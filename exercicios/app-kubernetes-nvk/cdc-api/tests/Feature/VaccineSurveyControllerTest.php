<?php

namespace Tests\Feature;

use App\User;
use App\VaccineSurveyCampaign;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VaccineSurveyControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function nonAuthUsersAccessUser() {
        $retrieve = $this->json('GET', '/api/campaigns/vaccine-survey');
        $retrieve->assertStatus(401);

        $create = $this->json('POST', '/api/campaigns/vaccine-survey');
        $create->assertStatus(401);

//        $destroy = $this->json('DELETE', '/api/campaigns/vaccine-survey');
//        $destroy->assertStatus(401);
    }

    /** @test */
    public function canCreate() {
        Carbon::setTestNow('2021-08-30 23:59:00');

        $payloads = [];
        $payloads[] = factory(VaccineSurveyCampaign::class)->state('no-dose')->make();
        $payloads[] = factory(VaccineSurveyCampaign::class)->state('first-dose')->make();
        $payloads[] = factory(VaccineSurveyCampaign::class)->state('single-dose')->make();
        $payloads[] = factory(VaccineSurveyCampaign::class)->state('both-doses')->make();

        $users = factory(User::class,4)->states(['approved', 'allowed-terms', 'not-first-time'])->create();

        foreach ($payloads as $key => $payload) {
            $response = $this->actingAs($users[$key], 'api')->json('POST', '/api/campaigns/vaccine-survey', $payload->toArray());
            $response
                ->assertStatus(200)
                ->assertJsonStructureExact([
                    'id',
                    'local_age_reached',
                    'first_dose',
                    'second_dose',
                    'user_id',
                    'updated_at',
                    'created_at',
                ]);

            $content = $response->decodeResponseJson();
            $this->assertDatabaseHas('campaigns_vaccine_survey', $content);
        }

    }

    /** @test */
    public function shouldReturnUnprocessableFields() {
        Carbon::setTestNow('2021-08-30 23:59:00');

        $payloads = [];
        $payloads[] = factory(VaccineSurveyCampaign::class)->make([
            'first_dose' => 'no',
            'second_dose' => 'yes'
        ]);
        $payloads[] = factory(VaccineSurveyCampaign::class)->make([
            'first_dose' => 'no',
            'second_dose' => 'n/a'
        ]);

        $users = factory(User::class, 2)->states(['approved', 'allowed-terms', 'not-first-time'])->create();

        foreach ($payloads as $key => $payload) {
            $response = $this->actingAs($users[$key], 'api')->json('POST', '/api/campaigns/vaccine-survey', $payload->toArray());
            $response
                ->assertStatus(422)
                ->assertJsonStructure([
                    'message',
                    'errors' => [
                        'first_dose',
                    ]
                ]);

            $this->assertDatabaseMissing('campaigns_vaccine_survey', $payload->toArray());
        }
    }

    /** @test */
    public function cannotCreateCampaignEnded() {
        Carbon::setTestNow('2021-08-31 00:00:00');

        $payload = factory(VaccineSurveyCampaign::class)->state('first-dose')->make();

        $response = $this->actingAs($this->admin, 'api')->json('POST', '/api/campaigns/vaccine-survey', $payload->toArray());
        $response->assertStatus(406);

        $this->assertDatabaseMissing('campaigns_vaccine_survey', $payload->toArray());
    }

    /** @test */
    public function canRetrieve() {
        Carbon::setTestNow('2021-08-30 23:59:00');

        $entry = factory(VaccineSurveyCampaign::class)->state('first-dose')->state('withUser')->create([
            'user_id' => $this->admin->id
        ]);

        $response = $this->actingAs($this->admin, 'api')->json('GET', '/api/campaigns/vaccine-survey');
        $response
            ->assertStatus(200)
            ->assertJsonStructureExact([
                'id',
                'local_age_reached',
                'first_dose',
                'second_dose',
                'user_id',
                'updated_at',
                'created_at',
            ]);
    }

    /** @test */
    public function cannotRetrieveCampaignEnded() {
        Carbon::setTestNow('2021-08-31 00:00:00');

        $entry = factory(VaccineSurveyCampaign::class)->state('first-dose')->state('withUser')->create([
            'user_id' => $this->admin->id
        ]);

        $response = $this->actingAs($this->admin, 'api')->json('GET', '/api/campaigns/vaccine-survey');
        $response->assertStatus(406);
    }
}
