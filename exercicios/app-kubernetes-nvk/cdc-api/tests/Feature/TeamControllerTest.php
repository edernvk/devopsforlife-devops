<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Faker\Factory;

class TeamControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function nonAuthUsersAccessTeam() {
        $index = $this->json('GET', '/api/teams');
        $index->assertStatus(401);

        $store = $this->json('POST', '/api/teams');
        $store->assertStatus(401);

        $show = $this->json('GET', '/api/teams/-1');
        $show->assertStatus(401);

        $update = $this->json('PUT', '/api/teams/-1');
        $update->assertStatus(401);

        $destroy = $this->json('DELETE', '/api/teams/-1');
        $destroy->assertStatus(401);
    }

    /** @test */
    public function canReturnListPaginatedTeams() {
        $team1 = $this->create('Team');
        $team2 = $this->create('Team');
        $team3 = $this->create('Team');

        $response = $this->actingAs($this->create('User', [], false), 'api')->json('GET', '/api/teams');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name', 'created_at'],
                ],
                'links' => ['first', 'last', 'prev', 'next'],
                'meta' => ['current_page', 'last_page', 'from', 'to', 'path', 'per_page', 'total']
            ]);
    }

    /** @test */
    public function canCreateTeam() {
        $faker = Factory::create('pt_BR');

        $dados = [ 'name' => $faker->text(25) ];

        $response = $this->actingAs($this->create('User', [], false), 'api')->json('POST', '/api/teams', $dados);

        $this->assertDatabaseHas('teams', $dados);

        $response
            ->assertJsonStructure(['id', 'name', 'created_at'])
            ->assertJson($dados)
            ->assertStatus(201);
    }

    /** @test */
    public function canReturnTeam() {
        $team = $this->create('Team');

        $response = $this->actingAs($this->create('User', [], false), 'api')->json('GET', "api/teams/$team->id");

        $response
            ->assertStatus(200)
            ->assertExactJson($team->toArray(['id', 'name', 'created_at']));
    }

    /** @test */
    public function teamNotFound() {
        $response = $this->actingAs($this->create('User', [], false), 'api')->json('GET', 'api/teams/-1');

        $response->assertStatus(404);
    }

    /** @test */
    public function canUpdateTeam() {
        $faker = Factory::create('pt_BR');
        $team = $this->create('Team');

        $dados = [ 'name' => $faker->name ];

        $response = $this->actingAs($this->create('User', [], false), 'api')->json('PUT', "/api/teams/$team->id", $dados);


        $dados['id'] = $team->id;
        $dados['created_at'] = (string) $team->created_at;

        $response
            ->assertStatus(200)
            ->assertExactJson($dados);
    }

    /** @test */
    public function canDeleteTeam() {
        $team = $this->create('Team');

        $response = $this->actingAs($this->create('User', [], false), 'api')->json('DELETE', "api/teams/$team->id");

        $response
            ->assertStatus(204)
            ->assertSee(null);

        $this->assertDatabaseMissing('teams', ['id' => $team->id]);
    }
}
