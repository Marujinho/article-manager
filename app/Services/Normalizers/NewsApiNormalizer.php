<?php

namespace App\Services\Normalizers;

use GuzzleHttp\Client;
use Carbon\Carbon;

class NewsApiNormalizer implements ArticleNormalizerInterface
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function handleNewArticles(): array
    {
        $fromDate = Carbon::yesterday()->toIso8601String();

        $url = 'https://newsapi.org/v2/everything';
        $apiKey = config('app.api_keys.news_api'); 

        $response = $this->client->get($url, [
            'query' => [
                'q' => 'tesla',
                'from' => $fromDate,
                'sortBy' => 'publishedAt',
                'apiKey' => $apiKey,
                'pageSize' => 10
            ],
        ]);
        $data = json_decode($response->getBody()->getContents(), true);

        return $this->normalizeData($data);
    }

    public function normalizeData(array $data): array
    {
        $articles = [];

        foreach ($data['articles'] as $article) {
            $articles[] = [
                'category' => 'All',
                'article' => $article['description'] ?? '',
                'source' => $article['source']['name'],
                'release_date' => date('Y-m-d', strtotime($article['publishedAt'])),
            ];
        }

        return $articles;
    }
}