<?php

namespace Tests\Jobs;

use App\Jobs\NewYorkTimesJob;
use App\Services\Article\ArticleService;
use Illuminate\Support\Facades\Bus;
use Tests\TestCase;

class NewYorkTimesJobTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_new_york_times_job_is_dispatched()
    {
        Bus::fake();

        NewYorkTimesJob::dispatch();

        Bus::assertDispatched(NewYorkTimesJob::class);
    }

    public function test_new_york_times_job_executes_correctly()
    {
        $articleService = app(ArticleService::class);

        $job = new NewYorkTimesJob();

        $result = $job->handle($articleService);

        $this->assertNull($result);
    }
}
