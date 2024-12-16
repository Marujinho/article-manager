<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Services\Article\ArticleService;
use Illuminate\Support\Facades\Log;


class NewsApiJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        $this->onQueue('newsapi');
    }

    /**
     * Execute the job.
     */
    public function handle(ArticleService $articleService) {

        try {
            $articles = $articleService->fetchExternalArticles('news_api');
            $articleService->storeNewArticles($articles);
           
        } catch (\Exception $e) {
            Log::error('Error in job: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}
