<?php

namespace Tests\Feature;

use Faker\Factory;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void {
        parent::setUp();
        $this->artisan('passport:install');
    }

    /**
     * @test
     */
    public function canAuthenticate() {
        $response = $this->json('POST', '/login/oauth', [
            'email' => $this->create('User', [], false)->email,
            'password' => 'password'
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonStructure(['token']);
    }

    /** @test */
    public function canRegisterUser() {
        $faker = Factory::create('pt_BR');

        $dados = [
            'name' => $faker->name,
            'email' => $faker->unique()->safeEmail,
            'registration' => $faker->cpf(false),
            'mobile' => $faker->phoneNumber,
            'avatar' => $faker->text(25),
            'city_id' => random_int(2000, 2500),
            'team_id' => random_int(1, 10),
            'password' => 'password',
            'password_confirmation' => 'password'
        ];

        $response = $this->json('POST', '/api/login/register', $dados);

        unset($dados['password_confirmation']);
        unset($dados['password']);

        $this->assertDatabaseHas('users', $dados);

        $response
            ->assertJsonStructure(['id', 'name', 'email', 'registration', 'mobile', 'avatar', 'city_id', 'city', 'team_id', 'team', 'created_at'])
            ->assertStatus(201);
    }
}
