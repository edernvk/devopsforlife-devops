<?php

namespace Tests\Feature;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

use Tests\TestCase;
use App\Newsletter;
use App\User;

class NewsletterControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function canReturnListPaginatedNewsletters() {
        factory(Newsletter::class, 10)->create();

        $admin = factory(User::class)->state('admin')->create();

        $response = $this->actingAs($admin, 'api')->json('GET', '/api/newsletters');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name', 'cover', 'created_at', 'updated_at'],
                ],
                'links' => ['first', 'last', 'prev', 'next'],
                'meta' => ['current_page', 'last_page', 'from', 'to', 'path', 'per_page', 'total']
            ]);
    }

    /** @test */
    public function canReturnListAllNewsletters() {
        factory(Newsletter::class, 10)->create();

        $admin = factory(User::class)->state('admin')->create();

        $response = $this->actingAs($admin, 'api')->json('GET', '/api/newsletters/all');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name', 'cover', 'created_at', 'updated_at'],
                ]
            ]);
    }

    /** @test */
    public function canReturnListRecentNewsletters() {
        $news = factory(Newsletter::class, 10)->create();

        $news = collect($news);
        $recentIds = $news->map(function ($n) {
            return $n->id;
        })->slice(-4)->values();

        $admin = factory(User::class)->state('admin')->create();

        $response = $this->actingAs($admin, 'api')->json('GET', '/api/newsletters/recent');

        $response
            ->assertStatus(200)
            ->assertJsonCount(4, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name', 'cover', 'created_at', 'updated_at']
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

        $newsletter = factory(Newsletter::class)->create();
        $payload = [];

        $create = $this->actingAs($admin, 'api')->json('POST', '/api/newsletters', $payload);
        $create
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'name',
                    'cover'
                ]
            ]);

        $update = $this->actingAs($admin, 'api')->json('PUT', '/api/newsletters/'.$newsletter->id, $payload);
        $update
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'name',
                    'cover'
                ]
            ]);
    }

    /** @test */
    public function canCreateNewsletter() {
        $admin = factory(User::class)->state('admin')->create();
        $payload = factory(Newsletter::class)->make();

        $request = $this->actingAs($admin, 'api')->json('POST', '/api/newsletters/', $payload->toArray());
        $request->assertStatus(201);
        $this->assertDatabaseHas('newsletters', $payload->toArray());
    }

    /** @test */
    public function canShowNewsletter() {
        $admin = factory(User::class)->state('admin')->create();
        $newsletter = factory(Newsletter::class)->create();

        $response = $this->actingAs($admin, 'api')->json('GET', '/api/newsletters/'.$newsletter->id);
        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'id', 'name', 'cover', 'created_at', 'updated_at'
            ]);
    }

    /** @test */
    public function canDeleteNewsletter() {
        $admin = factory(User::class)->state('admin')->create();
        $newsletter = factory(Newsletter::class)->create();

        $request = $this->actingAs($admin, 'api')->json('DELETE', '/api/newsletters/'.$newsletter->id);
        $request->assertStatus(204);
        $this->assertDatabaseMissing('newsletters', collect($newsletter)->toArray());
    }

    /** @test */
    public function canUploadCover() {
        $admin = factory(User::class)->state('admin')->create();

        $file = UploadedFile::fake()->image('cover.png');
        $response = $this->actingAs($admin, 'api')->json('POST', '/api/newsletters/cover', [
            'cover' => $file
        ]);
        $response
            ->assertStatus(200)
            ->assertJsonStructure(['url', 'path']);

        $dados = $response->getOriginalContent();
        Storage::disk('s3')->assertExists($dados['path']);
    }

    /** @test */
    public function shouldReturnValidationErrorCoverUpload() {
        $admin = factory(User::class)->state('admin')->create();

        $file = UploadedFile::fake()->image('cover.png');
        $response = $this->actingAs($admin, 'api')->json('POST', '/api/newsletters/cover', [
            'cover_fake' => $file
        ]);
        $response
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'cover'
                ]
            ]);
    }

}
