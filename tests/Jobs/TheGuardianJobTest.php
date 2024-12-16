<?php

namespace Tests\Jobs;

use App\Jobs\TheGuardianJob;
use App\Services\Article\ArticleService;
use Illuminate\Support\Facades\Bus;
use Tests\TestCase;

class TheGuardianJobTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_the_guardian_job_is_dispatched()
    {
        Bus::fake();

        TheGuardianJob::dispatch();

        Bus::assertDispatched(TheGuardianJob::class);
    }

    public function test_the_guardian_job_executes_correctly()
    {
        $articleService = app(ArticleService::class);

        $job = new TheGuardianJob();

        $result = $job->handle($articleService);

        $this->assertNull($result);
    }
}
