<?php

namespace Tests\Feature;

use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use App\Models\Article\Article;
use App\Services\Article\ArticleService;
use App\Services\Normalizers\NormalizerFactory;
use App\Services\Normalizers\ArticleNormalizerInterface;
use Database\Factories\ArticleFactory;
use Database\Factories\PreferenceFactory;
use Database\Factories\CategoryFactory;



class ArticleControllerTest extends TestCase
{
    // use RefreshDatabase;
    /**
     * A basic feature test example.
     */

    public function test_fetches_external_articles_successfully_from_the_Guardian()
    {
        $sourceName = 'the_guardian';

        $articleService = app(\App\Services\Article\ArticleService::class);

        $articleData = $articleService->fetchExternalArticles($sourceName);

        $this->assertNotEmpty($articleData, 'The external API did not return any data.');

        $this->assertIsArray($articleData, 'The API response is not an array.');
    }
    
    public function test_stores_articles_successfully()
    {
        $articles = [
            [
                'category' => 'Technology',
                'source' => 'Tech Blog',
                'article' => 'New tech innovation released',
                'release_date' => '2024-12-01',
            ],
            [
                'category' => 'Science',
                'source' => 'Science Daily',
                'article' => 'New discovery in quantum physics',
                'release_date' => '2024-12-05',
            ]
        ];

        app(ArticleService::class)->storeNewArticles($articles);

        foreach ($articles as $article) {
            $this->assertDatabaseHas('articles', [
                'category' => $article['category'],
                'source' => $article['source'],
                'article' => $article['article'],
                'release_date' => $article['release_date'],
            ]);
        }
    }

    public function test_returns_articles_filtered_by_specific_keyword()
    {   
        $user = UserFactory::new()->create();

        $this->actingAs($user);

        ArticleFactory::new()->count(2)->create();
        ArticleFactory::new()->create([
            'article' => 'The Verge is a technology news website. It covers various topics in tech and culture.',
            'category' => 'Technology',
            'source' => 'Online',
            'release_date' => now()->toDateString(),
        ]);

        $response = $this->get('/api/article/filtered-view?keyword=Verge');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data',
                    'links',
                    'current_page',
                    'total',
                    'per_page',
                ]);
    }

    public function test_get_article_by_id_success()
    {
        $user = UserFactory::new()->create();

        $this->actingAs($user);

        $article = ArticleFactory::new()->create(); 
       
        $response = $this->getJson('/api/article/view-article/' . $article->id);

        $response->assertStatus(200);

        $response->assertJson([
            'id' => $article->id,
        ]);
    }

    //test errors
    public function test_returns_empty_when_word_does_not_exist()
    {
        $user = UserFactory::new()->create();

        $this->actingAs($user);

        $nonExistentWord = "XXXXXXXXXX";

        $response = $this->get('/api/article/filtered-view?keyword=' . $nonExistentWord);

        $response->assertStatus(200);

        $response->assertJsonFragment(['data' => []]);

        $response->assertJsonCount(0, 'data');

    } 

    public function test_get_article_by_id_not_found()
    {
        $user = UserFactory::new()->create();

        $this->actingAs($user);

        $nonExistentId = 99999999; //outrageous id example

        $response = $this->getJson('/api/article/view-article/' . $nonExistentId);

        $response->assertStatus(404);

        
    }

    public function test_returns_error_message_for_unknown_source()
    {
        $sourceName = 'invalid_source';

        $articleService = app(\App\Services\Article\ArticleService::class);

        try {
            $articleService->fetchExternalArticles($sourceName);
        } catch (\InvalidArgumentException $e) {
            $this->assertEquals("Unknown source: {$sourceName}", $e->getMessage());
            return;
        }

        $this->fail('Expected InvalidArgumentException was not thrown.');
    }
}
