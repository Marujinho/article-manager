<?php

namespace App\Services\Normalizers;

use GuzzleHttp\Client;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class NewYorkTimesNormalizer implements ArticleNormalizerInterface
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function handleNewArticles(): array
    {
        $fromDate = Carbon::today()->toIso8601String();

        $url = 'https://api.nytimes.com/svc/search/v2/articlesearch.json';
        $apiKey = config('app.api_keys.new_york_times'); 

        $response = $this->client->get($url, [
                'query' => [
                'begin_date' => $fromDate,
                'end_date' => $fromDate,
                'sort' => 'newest',
                'api-key' => $apiKey,
                'page' => 1,
                'pege-size' => 10,
            ],
        ]);
        
        $data = json_decode($response->getBody()->getContents(), true);
        
        return $this->normalizeData($data);
    }

    public function normalizeData(array $data): array
    {
        $articles = [];

        foreach ($data['response']['docs'] as $article) {
            $articles[] = [
                'category' => $article['keywords'][0]['value'] ?? 'General',
                'article' => $article['lead_paragraph'] ?? '',
                'source' => $article['source'] ?? 'The New York Times',
                'release_date' => date('Y-m-d', strtotime($article['pub_date'])),
            ];
        }
        
        return $articles;
    }
}