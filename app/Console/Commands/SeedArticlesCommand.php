<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Article\ArticleService;

class SeedArticlesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:seed-articles-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(ArticleService $articleService, )
    {
        $sources = ["news_api", "new_york_times", "the_guardian"];

        foreach ($sources as $source) {
            $articles = $articleService->fetchExternalArticles($source);
            $articleService->storeNewArticles($articles);
        }
    }
}
