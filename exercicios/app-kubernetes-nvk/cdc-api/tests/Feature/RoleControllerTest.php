<?php

namespace Tests\Feature;

use App\Role;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RoleControllerTest extends TestCase
{
    /** @test */
    public function forbiddenRolesEndpoints() {
        $store = $this->actingAs($this->create('User', [], false), 'api')->json('POST', '/api/roles');
        $store->assertStatus(403);

        $update = $this->actingAs($this->create('User', [], false), 'api')->json('PUT', '/api/roles/-1');
        $update->assertStatus(403);

        $destroy = $this->actingAs($this->create('User', [], false), 'api')->json('DELETE', '/api/roles/-1');
        $destroy->assertStatus(403);
    }

    /** @test */
    public function canShowAllRoles() {
        $response = $this->actingAs($this->create('User', [], false), 'api')->json('GET', '/api/roles');

        $response
            ->assertStatus(200)
            ->assertJsonStructure(['*' => ['id', 'name', 'description', 'created_at']]);
    }

    /** @test */
    public function canReturnRole() {
        $response = $this->actingAs($this->create('User', [], false), 'api')->json('GET', "api/roles/1");

        $roles = Role::find(1);

        $response
            ->assertStatus(200)
            ->assertJsonFragment($roles->toArray());
    }
}
