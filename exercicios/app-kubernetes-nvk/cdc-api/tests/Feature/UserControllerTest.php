<?php

namespace Tests\Feature;

use App\User;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Faker\Factory;
use Illuminate\Support\Facades\Log;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function nonAuthUsersAccessUser() {
        $index = $this->json('GET', '/api/users');
        $index->assertStatus(401);

        $store = $this->json('POST', '/api/users');
        $store->assertStatus(401);

        $show = $this->json('GET', '/api/users/-1');
        $show->assertStatus(401);

        $update = $this->json('PUT', '/api/users/-1');
        $update->assertStatus(401);

        $destroy = $this->json('DELETE', '/api/users/-1');
        $destroy->assertStatus(401);

        $birthdays = $this->json('GET', '/api/users/birthday');
        $birthdays->assertStatus(401);
    }

    /** @test */
    public function canReturnListPaginatedUsers() {
        $admin = factory(User::class)->states('admin')->create();
        factory(User::class, 5)->create();

        $response = $this->actingAs($admin, 'api')->json('GET', '/api/users');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'email',
                        'mobile',
                        'registration',
                        'avatar',
                        'city_id',
                        'city',
                        'team_id',
                        'team',
                        'created_at',
                        'roles'
                    ],
                ],
                'links' => [
                    'first',
                    'last',
                    'prev',
                    'next'
                ],
                'meta' => [
                    'current_page',
                    'last_page',
                    'from',
                    'to',
                    'path',
                    'per_page',
                    'total'
                ]
            ]);
    }

    /** @test */
    public function canReturnAllUnpaginatedUsersSimplified() {
        $admin = factory(User::class)->states('admin')->create();
        factory(User::class, 10)->create();

        $response = $this->actingAs($admin, 'api')->json('GET', '/api/users/all');

        $response
            ->assertStatus(200)
            ->assertJsonStructureExact([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'cpf',
                        'email',
                        'registration',
                        'avatar',
                        'team_id',
                        'approved'
                    ],
                ]
            ]);
    }

    /** @test */
    public function canCreateUser() {
        $admin = factory(\App\User::class)->state('admin')->create();

        $usuario = factory(\App\User::class)->make();
        $payload = $usuario->toArray();
        $payload['mobile'] = '(18) 99999-8888'; //testing for form requests' prepareForValidation
        $payload['password'] = 'senha123123';
        $payload['password_confirmation'] = 'senha123123';
        $payload['roles'] = ['1'];

        $response = $this->actingAs($admin, 'api')->json('POST', '/api/users', $payload);

        unset($payload['password_confirmation']);
        unset($payload['password']);

        $response
            ->assertStatus(201)
            ->assertJsonStructureExact([
                'allow_terms',
                'approved',
                'avatar',
                'birth_date',
                'city' => [
                    'id',
                    'name',
                    'state_id'
                ],
                'city_id',
                'cpf',
                'created_at',
                'email',
                'first_time',
                'id',
                'mobile',
                'name',
                'registration',
                'roles' => [
                    '*' => [
                        'description',
                        'id',
                        'name',
                        'pivot'
                    ]
                ],
                'team' => [
                    'id',
                    'name'
                ],
                'team_id'
            ]);

        $this->assertDatabaseHas('users', [
            'name' => $payload['name'],
            'email' => $payload['email']
        ]);
    }

    /** @test */
    public function canReturnUser() {
        $user = factory(User::class)->state('colaborador')->create();

        $response = $this->actingAs($user, 'api')->json('GET', 'api/users/'.$user->id);

//        $dataUser = $user->toArray(['id', 'name', 'email', 'mobile', 'registration', 'avatar', 'city_id', 'team_id', 'created_at']);
//        unset($dataUser['city']);
//        unset($dataUser['team']);
        $dataUser = collect($user);
        $dataUser->forget(['city', 'team']);

        $response
            ->assertStatus(200)
            ->assertJsonFragment($dataUser->toArray());
    }

    /** @test */
    public function userNotFound() {
        $response = $this->actingAs($this->create('User', [], false), 'api')->json('GET', 'api/users/-1');

        $response->assertStatus(404);
    }

    /** @test */
    public function canUpdateUser() {
        $admin = factory(\App\User::class)->state('admin')->create();
        $user = factory(\App\User::class)->state('colaborador')->create();

        $payload = $user->toArray();
        $payload['mobile'] = '(18) 99999-8888'; //testing for form requests' prepareForValidation
        $payload['password'] = 'senha123123';
        $payload['password_confirmation'] = 'senha123123';
        $payload['roles'] = ['1'];

        $response = $this->actingAs($admin, 'api')->json('PUT', "/api/users/$user->id", $payload);

        Log::info($response->getContent());

        $response
            ->assertStatus(200)
            ->assertJsonStructureExact([
                'allow_terms',
                'approved',
                'avatar',
                'birth_date',
                'city' => [
                    'id',
                    'name',
                    'state_id'
                ],
                'city_id',
                'cpf',
                'created_at',
                'email',
                'first_time',
                'id',
                'mobile',
                'name',
                'registration',
                'roles' => [
                    '*' => [
                        'description',
                        'id',
                        'name',
                        'pivot'
                    ]
                ],
                'team' => [
                    'id',
                    'name'
                ],
                'team_id'
            ]);
    }

    /** @test */
    public function canUpdatePassword() {
        $admin = factory(User::class)->state('admin')->create();
        $notAdmin = factory(User::class)->state('colaborador')->create();

        $dados = [
            'password' => 'password_updated',
            'password_confirmation' => 'password_updated'
        ];

        $responseAdmin = $this->actingAs($admin, 'api')->json('PUT', 'api/users/'.$notAdmin->id.'/update-password', $dados);
        $responseAdmin->assertStatus(200);

        $responseSelf = $this->actingAs($notAdmin, 'api')->json('PUT', 'api/users/'.$notAdmin->id.'/update-password', $dados);
        $responseSelf->assertStatus(200);
    }

    /** @test */
    public function canDeleteUser() {
        $user = $this->create('User');

        $response = $this->actingAs($this->create('User', [], false), 'api')->json('DELETE', "api/users/$user->id");

        $response
            ->assertStatus(204)
            ->assertSee(null);

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    /** @test */
    public function canApproveUser() {
        $user = $this->create('User');

        $response = $this->actingAs($this->create('User', [], false), 'api')->json('PUT', "api/users/$user->id/approve", []);

        $response->assertStatus(200);
    }

    /** @test */
    public function canDisapproveUser() {
        $user = $this->create('User');

        $response = $this->actingAs($this->create('User', [], false), 'api')->json('PUT', "api/users/$user->id/disapprove", []);

        $response->assertStatus(200);
    }

    /** @test */
    public function shoudlReturnBirthdays() {
        $admin = factory(User::class)->state('admin')->create();
        factory(User::class, 3)->states(['aniversario-hoje', 'approved'])->create();
        factory(User::class, 3)->states(['aniversario-amanha', 'approved'])->create();

        $response = $this->actingAs($admin, 'api')->json('GET', '/api/users/birthday');

        Log::info($response->getContent());

        $response
            ->assertStatus(200)
            ->assertJsonStructureExact([
                'today',
                'tomorrow'
            ]);
    }
}
