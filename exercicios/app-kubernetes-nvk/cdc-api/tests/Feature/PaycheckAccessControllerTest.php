<?php

namespace Tests\Feature;

use App\PaycheckAccess;
use App\User;
use Faker\Factory;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PaycheckAccessControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function nonAuthUsersAccessUser() {
        $index = $this->json('GET', '/api/users/1/paycheck-access');
        $index->assertStatus(401);

        $update = $this->json('POST', '/api/users/1/paycheck-access');
        $update->assertStatus(401);

        $destroy = $this->json('PUT', '/api/users/1/paycheck-access');
        $destroy->assertStatus(401);

        $destroy = $this->json('DELETE', '/api/users/1/paycheck-access');
        $destroy->assertStatus(401);
    }

    /** @test */
    public function shouldReturnNotFound() {
        $admin = factory(User::class)->state('admin')->create();
        $notAdmin = factory(User::class)->state('colaborador')->create();

        $responseAsAdmin = $this->actingAs($admin, 'api')->json('GET', '/api/users/'.$notAdmin->id.'/paycheck-access');
        $responseAsAdmin->assertStatus(404);

        $responseSelf = $this->actingAs($notAdmin, 'api')->json('GET', '/api/users/'.$notAdmin->id.'/paycheck-access');
        $responseSelf->assertStatus(404);

    }

    /** @test */
    public function shouldBlockForbiddenAccess() {
        $notAdmin = factory(User::class)->state('colaborador')->create();
        $self = factory(User::class)->state('colaborador')->create();

        $notAdminAccessData = factory(PaycheckAccess::class)->create([
            'cpf' => $self->cpf,
            'user_id' => $self->id
        ]);

        $faker = Factory::create('pt_BR');
        $payload = [
            'email' => $faker->email(),
            'password' => 'test-paycheckpassword123'
        ];

        $show = $this->actingAs($notAdmin, 'api')->json('GET', '/api/users/'.$self->id.'/paycheck-access');
        $show->assertStatus(403);

        $create = $this->actingAs($notAdmin, 'api')->json('POST', '/api/users/'.$self->id.'/paycheck-access', $payload);
        $create->assertStatus(403);

        $update = $this->actingAs($notAdmin, 'api')->json('PUT', '/api/users/'.$self->id.'/paycheck-access', $payload);
        $update->assertStatus(403);

        $delete = $this->actingAs($notAdmin, 'api')->json('DELETE', '/api/users/'.$self->id.'/paycheck-access');
        $delete->assertStatus(403);
    }

    /** @test */
    public function shouldReturnUnprocessableFields() {
        $admin = factory(User::class)->state('admin')->create();
        $notAdmin = factory(User::class)->state('colaborador')->create();

        $payload = [
            'email' => 'naoehumemail',
            'password' => ''
        ];

        $create = $this->actingAs($admin, 'api')->json('POST', '/api/users/'.$notAdmin->id.'/paycheck-access', $payload);
        $create
            ->assertStatus(422)
            ->assertJsonStructureExact([
                'message',
                'errors' => [
                    'email',
                    'password'
                ]
            ]);

        $update = factory(PaycheckAccess::class)->create([
            'cpf' => $notAdmin->cpf,
            'user_id' => $notAdmin->id
        ]);

        $update = $this->actingAs($admin, 'api')->json('PUT', '/api/users/'.$notAdmin->id.'/paycheck-access', $payload);
        $update
            ->assertStatus(422)
            ->assertJsonStructureExact([
                'message',
                'errors' => [
                    'email',
                    'password'
                ]
            ]);


    }

    /** @test */
    public function canShowPaycheckAccess() {
        $admin = factory(User::class)->state('admin')->create();
        $notAdmin = factory(User::class)->state('colaborador')->create();

        $adminAccessData = factory(PaycheckAccess::class)->create([
            'cpf' => $admin->cpf,
            'user_id' => $admin->id
        ]);
        $notAdminAccessData = factory(PaycheckAccess::class)->create([
            'cpf' => $notAdmin->cpf,
            'user_id' => $notAdmin->id
        ]);

        $response1 = $this->actingAs($notAdmin, 'api')->json('GET', '/api/users/'.$notAdmin->id.'/paycheck-access');
        $response1
            ->assertStatus(200)
            ->assertJsonStructureExact([
                "email",
                "password",
                "cpf",
                "user" => [
                    "name",
                    "cpf",
                    "avatar"
                ]
            ]);

        $response2 = $this->actingAs($admin, 'api')->json('GET', '/api/users/'.$notAdmin->id.'/paycheck-access');
        $response2
            ->assertStatus(200)
            ->assertJsonStructureExact([
                "email",
                "password",
                "cpf",
                "user" => [
                    "name",
                    "cpf",
                    "avatar"
                ]
            ]);
    }

    /** @test */
    public function canCreatePaycheckAccess() {
        $admin = factory(User::class)->state('admin')->create();
        $notAdmin = factory(User::class)->state('colaborador')->create();

        $faker = Factory::create('pt_BR');
        $payload = [
            'email' => $faker->email(),
            'password' => 'test-paycheckpassword123'
        ];

        $request = $this->actingAs($admin, 'api')->json('POST', '/api/users/'.$notAdmin->id.'/paycheck-access', $payload);
        $request
            ->assertStatus(201)
            ->assertJsonStructureExact([
                "email",
                "password",
                "cpf",
                "user" => [
                    "name",
                    "cpf",
                    "avatar"
                ]
            ]);
        $this->assertDatabaseHas('paycheck_access', [
            'cpf' => $notAdmin->cpf,
            'user_id' => $notAdmin->id
        ]);
    }

    /** @test */
    public function canUpdatePaycheckAccess() {
        $admin = factory(User::class)->state('admin')->create();
        $notAdmin = factory(User::class)->state('colaborador')->create();

        $notAdminAccessData = factory(PaycheckAccess::class)->create([
            'cpf' => $notAdmin->cpf,
            'user_id' => $notAdmin->id
        ]);

        $faker = Factory::create('pt_BR');
        $payload = [
            'email' => $faker->email(),
            'password' => 'test-paycheckpassword123-changed'
        ];

        $request = $this->actingAs($admin, 'api')->json('PUT', '/api/users/'.$notAdmin->id.'/paycheck-access', $payload);
        $request
            ->assertStatus(200)
            ->assertJsonStructureExact([
                "email",
                "password",
                "cpf",
                "user" => [
                    "name",
                    "cpf",
                    "avatar"
                ]
            ]);
        $response = $request->getOriginalContent();
        $this->assertEquals($payload['password'], $response['password']);
        $this->assertDatabaseHas('paycheck_access', [
            'cpf' => $notAdmin->cpf,
            'user_id' => $notAdmin->id
        ]);
    }

    /** @test */
    public function canDeletePaycheckAccess() {
        $admin = factory(User::class)->state('admin')->create();
        $notAdmin = factory(User::class)->state('colaborador')->create();

        $notAdminAccessData = factory(PaycheckAccess::class)->create([
            'cpf' => $notAdmin->cpf,
            'user_id' => $notAdmin->id
        ]);

        $request = $this->actingAs($admin, 'api')->json('DELETE', '/api/users/'.$notAdmin->id.'/paycheck-access');
        $request->assertStatus(204);
        $this->assertDatabaseMissing('paycheck_access', $notAdminAccessData->toArray());
    }
}
