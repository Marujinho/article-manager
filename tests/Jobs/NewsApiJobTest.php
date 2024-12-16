<?php

namespace Tests\Jobs;

use App\Jobs\NewsApiJob;
use App\Services\Article\ArticleService;
use Illuminate\Support\Facades\Bus;
use Tests\TestCase;

class NewsApiJobTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_news_api_job_is_dispatched()
    {
        Bus::fake();

        NewsApiJob::dispatch();

        Bus::assertDispatched(NewsApiJob::class);
    }

    public function test_news_api_job_executes_correctly()
    {
        $articleService = app(ArticleService::class);

        $job = new NewsApiJob();

        $result = $job->handle($articleService);

        $this->assertNull($result);
    }
}
