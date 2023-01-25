<?php

namespace Tests\Feature;

use App\NewsletterNews;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class NewsletterNewsControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function shouldBlockUnauthenticatedAccess() {
        $all = $this->json('GET', '/api/newsletters-news/all');
        $all->assertStatus(401);

        $show = $this->json('GET', '/api/newsletters-news/1');
        $show->assertStatus(401);

        $store = $this->json('POST', '/api/newsletters-news');
        $store->assertStatus(401);

        $update = $this->json('PUT', '/api/newsletters-news/-1');
        $update->assertStatus(401);

        $destroy = $this->json('DELETE', '/api/newsletters-news/-1');
        $destroy->assertStatus(401);
    }

    /** @test */
    public function shouldBlockForbiddenAccess() {
        $newsletterNews = factory(NewsletterNews::class)->create();
        $payload = factory(NewsletterNews::class)->make();

        $store = $this->actingAs($this->notAdmin, 'api')->json('POST', '/api/newsletters-news', $payload->toArray());
        $store->assertStatus(403);

        $update = $this->actingAs($this->notAdmin, 'api')->json('PUT', '/api/newsletters-news/'.$newsletterNews->id, $payload->toArray());
        $update->assertStatus(403);

        $destroy = $this->actingAs($this->notAdmin, 'api')->json('DELETE', '/api/newsletters-news/'.$newsletterNews->id);
        $destroy->assertStatus(403);
    }

    /** @test */
    public function canReturnListPaginated() {
        factory(NewsletterNews::class, 5)->create();

        $response = $this->actingAs($this->admin, 'api')->json('GET', '/api/newsletters-news');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'title', 'content'],
                ],
                'links' => ['first', 'last', 'prev', 'next'],
                'meta' => ['current_page', 'last_page', 'from', 'to', 'path', 'per_page', 'total']
            ]);
    }

    /** @test */
    public function canReturnListAll()
    {
       factory(NewsletterNews::class, 5)->create();

        $response = $this->actingAs($this->admin, 'api')->json('GET', '/api/newsletters-news/all');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'content',
                        'created_at',
                        'updated_at'
                    ]
                ]
            ]);
    }

    /** @test */
    public function canReturnListRecents()
    {
        $news = factory(NewsletterNews::class, 10)->create();

        $news = collect($news);
        $recentIds = $news->map(function ($n) {
            return $n->id;
        })->slice(-3)->values();

        $response = $this->actingAs($this->admin, 'api')->json('GET','/api/newsletters-news/recents');

        $response
            ->assertStatus(200)
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'title',
                        'content'
                    ]
                ]
            ]);

        $recentsResponseIds = collect($response->getOriginalContent())->map(function ($recent) {
            return $recent->id;
        })->values();

        $this->assertEqualsCanonicalizing($recentIds, $recentsResponseIds);
    }

    /** @test */
    public function shouldReturnUnprocessableFields()
    {
        $payload = [];

        $create = $this->actingAs($this->admin, 'api')->json('POST', '/api/newsletters-news', $payload);
        $create
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'title',
                    'content'
                ]
            ]);

        $newsletterNews = factory(NewsletterNews::class)->create();
        $payload = factory(NewsletterNews::class)->make([
            'title' => 0,
            'content' => 2
        ]);
        $update = $this->actingAs($this->admin, 'api')->json('PUT', '/api/newsletters-news/'. $newsletterNews->id, $payload->toArray());
        $update
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'title',
                    'content',
                ]
            ]);
    }

    /** @test */
    public function canCreate()
    {
        $payload = factory(NewsletterNews::class)->make();

        $response = $this->actingAs($this->admin, 'api')->json('POST', '/api/newsletters-news', $payload->toArray());
        $response
            ->assertStatus(201)
            ->assertJsonStructure([
                'title',
                'content',
                'thumbnail',
                'created_at'
            ]);

        $this->assertDatabaseHas($this->getTable('NewsletterNews'), $payload->toArray());
    }

    /** @test */
    public function canShow()
    {
        $newsletterNews = factory(NewsletterNews::class)->create();

        $response = $this->actingAs($this->admin, 'api')->json('GET', '/api/newsletters-news/'.$newsletterNews->id);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'title',
                    'content',
                    'thumbnail',
                    'created_at'
                ]
            ]);
    }

    /** @test */
    public function canUpdate()
    {
        $newsletterNews = factory(NewsletterNews::class)->create();

        $payload = factory(NewsletterNews::class)->make();
        $justField = collect($payload->toArray())->forget(['created_at', 'updated_at']);

        $response = $this->actingAs($this->admin, 'api')->json('PUT', '/api/newsletters-news/'.$newsletterNews->id, $justField->toArray());

        $response
            ->assertStatus(200)
            ->dump()
            ->assertJsonStructure([
                'data' => [
                    'title',
                    'content',
                    'thumbnail',
                    'created_at'
                ]
            ]);

        $this->assertDatabaseHas($this->getTable('NewsletterNews'), $justField->toArray());
    }

    /** @test */
    public function canDelete()
    {
        $newsletterNews = factory(NewsletterNews::class)->create();

        $response = $this->actingAs($this->admin, 'api')->json('DELETE', '/api/newsletters-news/'.$newsletterNews->id);

        $response->assertStatus(204);
        $this->assertDatabaseMissing($this->getTable('NewsletterNews'), collect($newsletterNews)->toArray());
    }
}
