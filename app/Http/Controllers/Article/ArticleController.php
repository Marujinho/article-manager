<?php

namespace App\Http\Controllers\Article;

use Illuminate\Http\Request;
use App\Services\Article\ArticleService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Article\ArticlePaginationRequest;
use App\Http\Requests\Article\ArticleViewRequest;
use App\Http\Requests\Article\FilterArticleByWordRequest;
use App\Http\Requests\Article\FilterArticleByCategoryRequest;
use App\Models\Article\Article;

class ArticleController extends Controller
{
    protected $articleService;

    public function __construct(ArticleService $articleService)
    {
        $this->articleService = $articleService;
    }

    public function list(ArticlePaginationRequest $request)
    {
        $articles = $this->articleService->getPaginatedArticles($request);

        $user = $request->user();

        return response()->json([$articles, $user], 200, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function view(Article $article)
    {
        return response()->json($article, 200, [], JSON_PRETTY_PRINT);
    }

    public function filterArticleByWord(FilterArticleByWordRequest $request)
    {
        $articles = $this->articleService->getPaginatedFilteredArticles($request);

        return response()->json($articles, 200, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function getPreferedArticles()
    {   
        $articles = $this->articleService->getPaginatedPreferedArticles();

        return response()->json($articles, 200, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

}