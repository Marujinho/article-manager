<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Services\Article\ArticleService;
use Illuminate\Support\Facades\Log;

class NewYorkTimesJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        $this->onQueue('newyorktimes');
    }

    /**
     * Execute the job.
     */
    public function handle(ArticleService $articleService) {

        try {            
            $articles = $articleService->fetchExternalArticles('new_york_times');
            $articleService->storeNewArticles($articles);
           
        } catch (\Exception $e) {
            Log::error('Error in Job: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}
