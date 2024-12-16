<?php

namespace App\Services\Article;

use App\Models\Preference\Preference;
use App\Services\Normalizers\NormalizerFactory;
use App\Models\Article\Article;
use Illuminate\Support\Facades\Cache;
use Auth;

class ArticleService
{
    protected $normalizerFactory;
    protected $articleModel;

    public function __construct(NormalizerFactory $normalizerFactory, Article $articleModel)
    {
        $this->normalizerFactory = $normalizerFactory;
        $this->articleModel = $articleModel;
    }


    public function fetchExternalArticles($sourceName){
        $articleSource = $this->normalizerFactory->getArticleSource($sourceName);
        $articleData = $articleSource->handleNewArticles();

        return $articleData;
    }

    public function storeNewArticles($articles)
    {
        return Article::insert($articles);
    }

    public function getPaginatedArticles($request)
    {
        $perPage = $request->input('per_page', 10);

        $category = $request->input('category');

        $cacheKey = 'articles_per_page_' . $perPage . '_category_' . ($category ?? 'all');

        $articles = Cache::remember($cacheKey, 10, function ()  {
            return Article::paginate(15);
        });

        return $articles;
    }

    public function getArticleById($id)
    {
        $cacheKey = 'article_' . $id;
    
        $article = Cache::remember($cacheKey, 10, function () use ($id, $cacheKey) {
            return Article::findOrFail($id);
        });
        
        return $article;
    }

    public function getPaginatedFilteredArticles($request)
    {
        $perPage = 15;
        $keyWord =  $request->input('keyword');

        $cacheKey = 'articles_per_page_' . $perPage . '_keyWord_' . ($keyWord ?? 'all');

        $articles = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($perPage, $keyWord) {
            $query = Article::query();

            $query->where('article', 'like', '%' . $keyWord . '%')
                ->orWhere('source', 'like', '%' . $keyWord . '%')
                ->orWhere('category', 'like', '%' . $keyWord . '%');
            
            return $query->paginate($perPage);
        });

        return $articles;
    }

    public function getPaginatedPreferedArticles()
    {
        $perPage = 15;
        $userId = Auth::user()->id;

        $keycategories = Preference::where('user_id', $userId)
            ->join('categories', 'preferences.category_id', '=', 'categories.id')
            ->pluck('categories.name');
        
        $cacheKey = 'articles_per_page_' . $perPage . '_keycategory_' . ($keycategory ?? 'all');

        $articles = Cache::remember($cacheKey, 10, function () use ($keycategories, $perPage) {
            return Article::whereIn('category', $keycategories)->paginate($perPage);
        });

        return $articles;
    }

}