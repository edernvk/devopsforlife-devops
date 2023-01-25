<?php

namespace Tests\Feature;

use App\Message;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Faker\Factory;
use Illuminate\Support\Collection;

class MessageControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function nonAuthUsersAccessMessage() {
        $index = $this->json('GET', '/api/messages');
        $index->assertStatus(401);

        $store = $this->json('POST', '/api/messages');
        $store->assertStatus(401);

        $show = $this->json('GET', '/api/messages/-1');
        $show->assertStatus(401);

        $update = $this->json('PUT', '/api/messages/-1');
        $update->assertStatus(401);

        $destroy = $this->json('DELETE', '/api/messages/-1');
        $destroy->assertStatus(401);
    }

    /** @test */
    public function canReturnListPaginatedMessages() {
        $message1 = $this->create('Message');
        $message2 = $this->create('Message');
        $message3 = $this->create('Message');

        $response = $this->actingAs($this->admin, 'api')->json('GET', '/api/messages');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'from', 'title', 'description', 'to', 'created_at'],
                ],
                'links' => ['first', 'last', 'prev', 'next'],
                'meta' => ['current_page', 'last_page', 'from', 'to', 'path', 'per_page', 'total']
            ]);
    }

    /** @test */
    public function canReturnInboxMessagesAll() {
        $messages = factory(Message::class, 3)->states(['from-admin', 'to-everybody'])->create();
        $messagesToOthers = factory(Message::class, 2)->states(['from-admin', 'to-somebody']);
        $readMessages = factory(Message::class, 2)->states(['from-admin', 'to-everybody', 'all-read'])->create();

        $response = $this->actingAs($this->notAdmin, 'api')->json('GET', 'api/messages/inbox/'.$this->notAdmin->id.'/formatted');
        $response
            ->assertStatus(200)
            ->assertJsonStructureExact([
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'description',
                        'created_at',
                        'forHumans',
                        'publish_datetime',
                        'status_id',
                        'current_user_read',
                        'from',
                        'fromData' => [
                            'id',
                            'name',
                            'cpf',
                            'email',
                            'registration',
                            'avatar',
                            'team_id',
                            'approved'
                        ],
                        'name',
                        'avatar'
                    ],
                ],
                'links' => ['first', 'last', 'prev', 'next'],
                'meta' => ['current_page', 'last_page', 'from', 'to', 'path', 'per_page', 'total']
            ]);

        $totalMessages = $response->decodeResponseJson()['meta']['total'];
        $this->assertEquals(5, $totalMessages);

    }

    /** @test */
    public function canReturnInboxMessagesRead() {
        $messages = factory(Message::class, 5)->states(['from-admin', 'to-everybody'])->create();
        $messagesToOthers = factory(Message::class, 2)->states(['from-admin', 'to-somebody'])->create();
        $readMessages = factory(Message::class, 2)->states(['from-admin', 'to-everybody', 'all-read'])->create();

        $response = $this->actingAs($this->notAdmin, 'api')->json('GET', 'api/messages/inbox/'.$this->notAdmin->id.'/formatted/read');
        $response
            ->assertStatus(200)
            ->assertJsonStructureExact([
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'description',
                        'created_at',
                        'forHumans',
                        'publish_datetime',
                        'status_id',
                        'current_user_read',
                        'from',
                        'fromData' => [
                            'id',
                            'name',
                            'cpf',
                            'email',
                            'registration',
                            'avatar',
                            'team_id',
                            'approved'
                        ],
                        'name',
                        'avatar'
                    ],
                ],
                'links' => ['first', 'last', 'prev', 'next'],
                'meta' => ['current_page', 'last_page', 'from', 'to', 'path', 'per_page', 'total']
            ]);

        $totalReadMessages = $response->decodeResponseJson()['meta']['total'];
        $this->assertEquals(2, $totalReadMessages);
    }

    /** @test */
    public function canReturnInboxMessagesUnread() {
        $messages = factory(Message::class, 5)->states(['from-admin', 'to-everybody'])->create();
        $messagesToOthers = factory(Message::class, 2)->states(['from-admin', 'to-somebody'])->create();
        $readMessages = factory(Message::class)->states(['from-admin', 'to-everybody', 'all-read'])->create();

        $response = $this->actingAs($this->notAdmin, 'api')->json('GET', 'api/messages/inbox/'.$this->notAdmin->id.'/formatted/unread');
        $response
            ->assertStatus(200)
            ->assertJsonStructureExact([
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'description',
                        'created_at',
                        'forHumans',
                        'publish_datetime',
                        'status_id',
                        'current_user_read',
                        'from',
                        'fromData' => [
                            'id',
                            'name',
                            'cpf',
                            'email',
                            'registration',
                            'avatar',
                            'team_id',
                            'approved'
                        ],
                        'name',
                        'avatar'
                    ],
                ],
                'links' => ['first', 'last', 'prev', 'next'],
                'meta' => ['current_page', 'last_page', 'from', 'to', 'path', 'per_page', 'total']
            ]);

        $totalMessagesUnread = $response->decodeResponseJson()['meta']['total'];
        $this->assertEquals(5, $totalMessagesUnread);
    }

    /** @test */
    public function canSearchInboxMessagesAll() {
        $messages = factory(Message::class, 6)->states(['from-admin', 'to-everybody'])->create();
        $messagesToOthers = factory(Message::class, 2)->states(['from-admin', 'to-somebody'])->create([
            'title' => 'findme',
            'description' => 'findme'
        ]);
        $searchableMessages = factory(Message::class, 3)->states(['from-admin', 'to-everybody'])->create([
            'title' => 'findme',
            'description' => 'findme'
        ]);
        $searchableReadMessages = factory(Message::class, 4)->states(['from-admin', 'to-everybody', 'all-read'])->create([
            'title' => 'findme',
            'description' => 'findme'
        ]);

        $response = $this->actingAs($this->notAdmin, 'api')->json('GET', 'api/messages/inbox/'.$this->notAdmin->id.'/formatted?search=findme');
        $response
            ->assertStatus(200)
            ->assertJsonStructureExact([
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'description',
                        'created_at',
                        'forHumans',
                        'publish_datetime',
                        'status_id',
                        'current_user_read',
                        'from',
                        'fromData' => [
                            'id',
                            'name',
                            'cpf',
                            'email',
                            'registration',
                            'avatar',
                            'team_id',
                            'approved'
                        ],
                        'name',
                        'avatar'
                    ],
                ],
                'links' => ['first', 'last', 'prev', 'next'],
                'meta' => ['current_page', 'last_page', 'from', 'to', 'path', 'per_page', 'total']
            ]);

        $totalFoundMessages = $response->decodeResponseJson()['meta']['total'];
        $this->assertEquals(7, $totalFoundMessages); // read + unread but only adressed to him (auth user)
    }

    /** @test */
    public function canSearchInboxMessagesRead() {
        $messages = factory(Message::class, 5)->states(['from-admin', 'to-everybody'])->create();
        $messagesToOthers = factory(Message::class, 2)->states(['from-admin', 'to-somebody'])->create([
            'title' => 'findme',
            'description' => 'findme'
        ]);
        $readMessages = factory(Message::class, 2)->states(['from-admin', 'to-everybody', 'all-read'])->create();
        $searchableReadMessages = factory(Message::class, 2)->states(['from-admin', 'to-everybody', 'all-read'])->create([
            'title' => 'findme',
            'description' => 'findme'
        ]);
        $searchableMessages = factory(Message::class, 2)->states(['from-admin', 'to-everybody'])->create([
            'title' => 'findme',
            'description' => 'findme'
        ]);

        $response = $this->actingAs($this->notAdmin, 'api')->json('GET', 'api/messages/inbox/'.$this->notAdmin->id.'/formatted/read?search=findme');
        $response
            ->assertStatus(200)
            ->assertJsonStructureExact([
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'description',
                        'created_at',
                        'forHumans',
                        'publish_datetime',
                        'status_id',
                        'current_user_read',
                        'from',
                        'fromData' => [
                            'id',
                            'name',
                            'cpf',
                            'email',
                            'registration',
                            'avatar',
                            'team_id',
                            'approved'
                        ],
                        'name',
                        'avatar'
                    ],
                ],
                'links' => ['first', 'last', 'prev', 'next'],
                'meta' => ['current_page', 'last_page', 'from', 'to', 'path', 'per_page', 'total']
            ]);

        $totalFoundReadMessages = $response->decodeResponseJson()['meta']['total'];
        $this->assertEquals(2, $totalFoundReadMessages);
    }

    /** @test */
    public function canSearchInboxMessagesUnread() {
        $messages = factory(Message::class, 5)->states(['from-admin', 'to-everybody'])->create();
        $messagesToOthers = factory(Message::class, 2)->states(['from-admin', 'to-somebody'])->create([
            'title' => 'findme',
            'description' => 'findme'
        ]);
        $searchableMessages = factory(Message::class, 3)->states(['from-admin', 'to-everybody'])->create([
            'title' => 'findme',
            'description' => 'findme'
        ]);
        $readMessages = factory(Message::class, 4)->states(['from-admin', 'to-everybody', 'all-read'])->create();
        $searchableReadMessages = factory(Message::class, 6)->states(['from-admin', 'to-everybody', 'all-read'])->create([
            'title' => 'findme',
            'description' => 'findme'
        ]);

        $response = $this->actingAs($this->notAdmin, 'api')->json('GET', 'api/messages/inbox/'.$this->notAdmin->id.'/formatted/unread?search=findme');
        $response
            ->assertStatus(200)
            ->assertJsonStructureExact([
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'description',
                        'created_at',
                        'forHumans',
                        'publish_datetime',
                        'status_id',
                        'current_user_read',
                        'from',
                        'fromData' => [
                            'id',
                            'name',
                            'cpf',
                            'email',
                            'registration',
                            'avatar',
                            'team_id',
                            'approved'
                        ],
                        'name',
                        'avatar'
                    ],
                ],
                'links' => ['first', 'last', 'prev', 'next'],
                'meta' => ['current_page', 'last_page', 'from', 'to', 'path', 'per_page', 'total']
            ]);

        $totalFoundUnreadMessages = $response->decodeResponseJson()['meta']['total'];
        $this->assertEquals(3, $totalFoundUnreadMessages);
    }

    /** @test */
    public function canCreateMessage() {
        $faker = Factory::create('pt_BR');
        $user1 = $this->create('User');
        $user2 = $this->create('User');

        $dados = [
            'from' => random_int(1, 5),
            'title' => $faker->text(25),
            'description' => $faker->paragraphs(3, true),
            'to' => [$user1->id, $user2->id]
        ];

        $response = $this->actingAs($this->create('User', [], false), 'api')->json('POST', '/api/messages', $dados);

        unset($dados['to']);

        $response
            ->assertJsonStructure(['id', 'from', 'title', 'description', 'to', 'created_at'])
            ->assertJson($dados)
            ->assertStatus(201);

        unset($dados['to']);
        $this->assertDatabaseHas('messages', $dados);
    }

    /** @test */
    public function canReturnMessage() {
        $message = $this->create('Message');

        $response = $this->actingAs($this->create('User', [], false), 'api')->json('GET', "api/messages/$message->id");

        $response
            ->assertStatus(200)
            ->assertExactJson($message->toArray(['id', 'from', 'title', 'description', 'to', 'created_at']));
    }

    /** @test */
    public function messageNotFound() {
        $response = $this->actingAs($this->create('User', [], false), 'api')->json('GET', 'api/messages/-1');

        $response->assertStatus(404);
    }

    /** @test */
    public function canUpdateMessage() {
        $faker = Factory::create('pt_BR');
        $message = $this->create('Message');
        $user1 = $this->create('User');
        $user2 = $this->create('User');

        $dados = [
            'from' => random_int(1, 5),
            'title' => $faker->text(25),
            'description' => $faker->paragraphs(3, true),
            'to' => [$user1->id, $user2->id]
        ];

        $response = $this->actingAs($this->create('User', [], false), 'api')->json('PUT', "/api/messages/$message->id", $dados);

        $dados['id'] = $message->id;
        $dados['created_at'] = (string) $message->created_at;
        unset($dados['to']);

        $response
            ->assertStatus(200)
            ->assertJsonFragment($dados);
    }

    /** @test */
    public function canDeleteMessage() {
        $message = $this->create('Message');

        $response = $this->actingAs($this->create('User', [], false), 'api')->json('DELETE', "api/messages/$message->id");

        $response
            ->assertStatus(204)
            ->assertSee(null);

        $this->assertDatabaseMissing('messages', ['id' => $message->id]);
    }
}
