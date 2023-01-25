<?php

namespace Tests\Feature;

use App\ChristmasBasket;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChristmasBasketControllerTest extends TestCase
{
    use RefreshDatabase;

    const CAMPAIGN_DATE = [
        'INACTIVE' => '2021-10-17',
        'ACTIVE' => '2021-10-18',
        'FINISHED' => '2021-10-30'
    ];

    // runs before each test of this testcase
    public function setUp() : void {
        parent::setUp();

        \App\Campaign::updateOrCreate(
            [
                'title' => 'Entrega da Cesta de Natal 2021',
                'slug' => 'cesta-natal'
            ],
            [
                'description' => 'Abaixo insira as informações de endereço onde deve ser entregue a sua Cesta de Natal neste ano. Preencha todos os campos com atenção para que não haja inconformidades na entrega.',
                'entry_date' => '2021-10-18',
                'departure_date' => '2021-10-29'
            ]
        );

        // set tests with ACTIVE date by default
        Carbon::setTestNow($this::CAMPAIGN_DATE['ACTIVE']);
    }

    /** @test */
    public function shouldBlockUnautheticatedAccess()
    {
        $retrieve = $this->json('GET', 'api/campaigns/christmas-baskets');
        $retrieve->assertStatus(401);

        $save = $this->json('POST', 'api/campaigns/christmas-baskets');
        $save->assertStatus(401);
    }

    /** @test */
    public function canReturnCestaNatalCampaignDetails()
    {
        // cesta-natal campaign created at this test class setUp() above

        $response = $this->actingAs($this->notAdmin, 'api')
            ->json('GET', '/api/campaigns/cesta-natal');
        $response
            ->assertStatus(200)
            ->assertJsonStructureExact([
                'id',
                'title',
                'description',
                'slug',
                'entry_date',
                'departure_date',
                'created_at',
                'updated_at'
            ])
            ->assertJsonFragment([
                'title' => 'Entrega da Cesta de Natal 2021',
                'slug' => 'cesta-natal'
            ]);
    }

    /** @test */
    public function shouldReturnInactiveCestaNatalCampaign()
    {
        Carbon::setTestNow($this::CAMPAIGN_DATE['INACTIVE']);
        $response = $this->actingAs($this->notAdmin, 'api')
            ->json('GET', '/api/campaigns/cesta-natal');
        $response
            ->assertStatus(406)
            ->assertJsonStructureExact([
                'error' => [
                    'description',
                    'message'
                ]
            ])
            ->assertJsonFragment([
                'message' => 'Esta campanha ainda não está ativa.'
            ]);
    }

    /** @test */
    public function shouldReturnFinishedCestaNatalCampaign()
    {
        Carbon::setTestNow($this::CAMPAIGN_DATE['FINISHED']);
        $response = $this->actingAs($this->notAdmin, 'api')
            ->json('GET', '/api/campaigns/cesta-natal');
        $response
            ->assertStatus(406)
            ->assertJsonStructureExact([
                'error' => [
                    'description',
                    'message'
                ]
            ])
            ->assertJsonFragment([
                'message' => 'Esta campanha já foi encerrada.'
            ]);
    }

    /** @test */
    public function canCreateEntry()
    {
        $payload = factory(ChristmasBasket::class)->make([
            // this is here just for easy database assertion
            // the user_id field is ignored
            'user_id' => $this->notAdmin->id
        ]);

        $response = $this->actingAs($this->notAdmin, 'api')
            ->json('POST', '/api/campaigns/christmas-baskets', $payload->toArray());

        $response
        ->assertStatus(201)
        ->assertJsonStructureExact([
            'id',
            'user_id',
            'shipping_address_street_name',
            'shipping_address_number',
            'shipping_address_neighbourhood',
            'shipping_address_zipcode',
            'shipping_address_city',
            'shipping_address_complement',
            'name_recipient',
            'degree_kinship',
            'suggestion',
            'created_at',
            'updated_at'
        ]);

        $this->assertDatabaseHas($this->getTable('ChristmasBasket'), $payload->toArray());
    }

    /** @test */
    public function canCreateEntryWithoutComplement()
    {
        $payload = factory(ChristmasBasket::class)->state('complementless')->make([
            // this is here just for easy database assertion
            // the user_id field is ignored
            'user_id' => $this->notAdmin->id
        ]);

        $response = $this->actingAs($this->notAdmin, 'api')
            ->json('POST', '/api/campaigns/christmas-baskets', $payload->toArray());

        $response
            ->assertStatus(201)
            ->assertJsonStructureExact([
                'id',
                'user_id',
                'shipping_address_zipcode',
                'shipping_address_street_name',
                'shipping_address_number',
                'shipping_address_neighbourhood',
                'shipping_address_city',
                'shipping_address_complement',
                'name_recipient',
                'degree_kinship',
                'suggestion',
                'created_at',
                'updated_at'
            ]);

        $this->assertDatabaseHas($this->getTable('ChristmasBasket'), $payload->toArray());
    }

    /** @test */
    public function shouldNotCreateWithInvalidFields()
    {
        $payload = factory(ChristmasBasket::class)->make([
            'user_id' => $this->notAdmin->id,
            'shipping_address_zipcode' => '',
            'shipping_address_number' => '',
            'name_recipient' => '',
            'degree_kinship' => ''
        ]);

        $response = $this->actingAs($this->notAdmin, 'api')
            ->json('POST', '/api/campaigns/christmas-baskets', $payload->toArray());

        $response
            ->assertStatus(422)
            ->assertJsonStructureExact([
                'message',
                'errors'=> [
                    'shipping_address_zipcode',
                    'shipping_address_number',
                    'name_recipient',
                    'degree_kinship'
                ]
            ])
            ->assertJsonFragment([
                'shipping_address_zipcode' => [
                    'O campo CEP para entrega é obrigatório.'
                ],
                'shipping_address_number' => [
                    'O campo número para entrega é obrigatório.'
                ],
                'name_recipient' => [
                    'O campo nome do destinatário é obrigatório.'
                ],
                'degree_kinship' => [
                    'O campo grau de parentesco é obrigatório.'
                ],
            ]);

        $this->assertDatabaseMissing($this->getTable('ChristmasBasket'), $payload->toArray());
    }

    /** @test */
    public function shouldNotCreateIfAlreadyCreated()
    {
        $created = factory(ChristmasBasket::class)->create([
            'user_id' => $this->notAdmin->id
        ]);

        $payload = factory(ChristmasBasket::class)->make([
            'user_id' => $this->notAdmin->id
        ]);

        $response = $this->actingAs($this->notAdmin, 'api')
            ->json('POST', '/api/campaigns/christmas-baskets', $payload->toArray());

        $response
            ->assertStatus(422)
            ->assertJsonStructureExact([
                'message',
                'errors'=> [
                    'user_id'
                ]
            ])
            ->assertJsonFragment([
                'user_id' => [
                    'Este usuário já respondeu este formulário.'
                ]
            ]);

        $this->assertDatabaseMissing($this->getTable('ChristmasBasket'), $payload->toArray());
    }

    /** @test */
    public function shouldNotCreateForOtherUser()
    {
        $strangeUser = factory(User::class)->create();
        $payload = factory(ChristmasBasket::class)->state('complementless')->make([
            'user_id' => $strangeUser->id
        ]);

        $response = $this->actingAs($this->notAdmin, 'api')
            ->json('POST', '/api/campaigns/christmas-baskets', $payload->toArray());
        $response
            ->assertStatus(201)
            ->assertJsonStructureExact([
                'id',
                'user_id',
                'shipping_address_zipcode',
                'shipping_address_street_name',
                'shipping_address_number',
                'shipping_address_neighbourhood',
                'shipping_address_city',
                'shipping_address_complement',
                'name_recipient',
                'degree_kinship',
                'suggestion',
                'created_at',
                'updated_at'
            ]);

        // database should have user_id which request auth was made
        $payload = $payload->toArray();
        $this->assertDatabaseHas($this->getTable('ChristmasBasket'), [
            'user_id' => $this->notAdmin->id,
            'shipping_address_zipcode' => $payload['shipping_address_zipcode'],
            'name_recipient' => $payload['name_recipient']
        ]);

        // database should not have user_id sent through fields
        $this->assertDatabaseMissing($this->getTable('ChristmasBasket'), [
            'user_id' => $strangeUser->id,
            'shipping_address_zipcode' => $payload['shipping_address_zipcode'],
            'name_recipient' => $payload['name_recipient']
        ]);
    }

}
