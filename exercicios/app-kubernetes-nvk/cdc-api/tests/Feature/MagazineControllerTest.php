<?php

namespace Tests\Feature;

use App\Magazine;
use App\User;
use Faker\Factory;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MagazineControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function nonAuthUsersAccessUser() {
        $index = $this->json('GET', '/api/magazines');
        $index->assertStatus(401);

        $index = $this->json('GET', '/api/magazines/1');
        $index->assertStatus(401);

        $update = $this->json('POST', '/api/magazines');
        $update->assertStatus(401);

        $destroy = $this->json('PUT', '/api/magazines/-1');
        $destroy->assertStatus(401);

        $destroy = $this->json('DELETE', '/api/magazines/-1');
        $destroy->assertStatus(401);

        $store = $this->json('GET', '/api/magazines/all');
        $store->assertStatus(401);

        $show = $this->json('POST', '/api/magazines/cover');
        $show->assertStatus(401);
    }

    /** @test */
    public function shouldBlockForbiddenAccess() {
        $notAdmin = factory(User::class)->state('colaborador')->create();

        $magazine = factory(Magazine::class)->create();

        $faker = Factory::create('pt_BR');
        $payload = [
            'title' => $faker->sentence(3),
            'link' => $faker->url
        ];

        $create = $this->actingAs($notAdmin, 'api')->json('POST', '/api/magazines', $payload);
        $create->assertStatus(403);

        $update = $this->actingAs($notAdmin, 'api')->json('PUT', '/api/magazines/'.$magazine->id, $payload);
        $update->assertStatus(403);

        $delete = $this->actingAs($notAdmin, 'api')->json('DELETE', '/api/magazines/'.$magazine->id);
        $delete->assertStatus(403);
    }

    /** @test */
    public function canReturnListPaginatedMagazines() {
        factory(Magazine::class, 3)->create();

        $admin = factory(User::class)->state('admin')->create();

        $response = $this->actingAs($admin, 'api')->json('GET', '/api/magazines');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'title', 'link', 'cover', 'created_at', 'updated_at'],
                ],
                'links' => ['first', 'last', 'prev', 'next'],
                'meta' => ['current_page', 'last_page', 'from', 'to', 'path', 'per_page', 'total']
            ]);
    }

    /** @test */
    public function canReturnListAllMagazines() {
        factory(Magazine::class, 5)->create();

        $admin = factory(User::class)->state('admin')->create();

        $response = $this->actingAs($admin, 'api')->json('GET', '/api/magazines/all');
        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'title', 'link', 'cover', 'created_at', 'updated_at']
                ]
            ]);
    }

    /** @test */
    public function canReturnListRecentMagazines() {
        $mags = factory(Magazine::class, 10)->create();

        $mags = collect($mags);
        $recentIds = $mags->map(function ($n) {
            return $n->id;
        })->slice(-4)->values();

        $admin = factory(User::class)->state('admin')->create();

        $response = $this->actingAs($admin, 'api')->json('GET', '/api/magazines/recent');
        $response
            ->assertStatus(200)
            ->assertJsonCount(4, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'title', 'link', 'cover', 'created_at', 'updated_at']
                ]
            ]);

        $responseRecentIds = collect($response->getOriginalContent())->map(function($r) {
            return $r->id;
        })->values();
        $this->assertEqualsCanonicalizing($recentIds, $responseRecentIds);
    }


    /** @test */
    public function shouldReturnUnprocessableFields() {
        $admin = factory(User::class)->state('admin')->create();

        $magazine = factory(Magazine::class)->create();

        $faker = Factory::create('pt_BR');
        // title and link fields are required
        $payload = [];

        $create = $this->actingAs($admin, 'api')->json('POST', '/api/magazines', $payload);
        $create
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'title',
                    'link'
                ]
            ]);

        $update = $this->actingAs($admin, 'api')->json('PUT', '/api/magazines/'.$magazine->id, $payload);
        $update
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'title',
                    'link'
                ]
            ]);
    }

    /** @test */
    public function canCreateMagazine() {
        $admin = factory(User::class)->state('admin')->create();

        $faker = Factory::create('pt_BR');
        $payload = [
            'title' => $faker->sentence(3),
            'link' => $faker->url
        ];

        $request = $this->actingAs($admin, 'api')->json('POST', '/api/magazines/', $payload);
        $request->assertStatus(201);
        $this->assertDatabaseHas('magazines', $payload);
    }

    /** @test */
    public function canShowMagazine() {
        $admin = factory(User::class)->state('admin')->create();

        $magazine = factory(Magazine::class)->create();

        $response = $this->actingAs($admin, 'api')->json('GET', '/api/magazines/'.$magazine->id);
        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'id', 'title', 'link', 'cover', 'created_at', 'updated_at'
            ]);
    }

    /** @test */
    public function canDeleteMagazine() {
        $admin = factory(User::class)->state('admin')->create();

        $magazine = factory(Magazine::class)->create();

        $request = $this->actingAs($admin, 'api')->json('DELETE', '/api/magazines/'.$magazine->id);
        $request->assertStatus(204);
        $this->assertDatabaseMissing('magazines', collect($magazine)->toArray());
    }

    /** @test */
    public function canUploadCover() {
        $admin = factory(User::class)->state('admin')->create();

        $file = UploadedFile::fake()->image('cover.png');
        $response = $this->actingAs($admin, 'api')->json('POST', '/api/magazines/cover', [
            'cover' => $file
        ]);
        $response
            ->assertStatus(200)
            ->assertJsonStructure(['url', 'path']);

        $dados = $response->getOriginalContent();
        Storage::disk('s3')->assertExists($dados['path']);
    }

    /** @test */
    public function shouldReturnNullCoverUpload() {
        $admin = factory(User::class)->state('admin')->create();

        $file = UploadedFile::fake()->image('cover.png');
        $response = $this->actingAs($admin, 'api')->json('POST', '/api/magazines/cover', [
            'cover_fake' => $file
        ]);
        $response->assertStatus(422);
    }
}
