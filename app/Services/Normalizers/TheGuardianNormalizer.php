<?php

namespace App\Services\Normalizers;

use GuzzleHttp\Client;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class TheGuardianNormalizer implements ArticleNormalizerInterface
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function handleNewArticles(): array
    {
        $fromDate = Carbon::yesterday()->toIso8601String();

        $url = 'https://content.guardianapis.com/search';
        $apiKey = config('app.api_keys.the_guardian'); 


        $response = $this->client->get($url, [
            'query' => [
                'from-date' => $fromDate,
                'page' => 1,
                'api-key' => $apiKey,
                'page-size' => 10,
            ],
        ]);

        $data = json_decode($response->getBody()->getContents(), true);
        
        return $this->normalizeData($data);
    }

    public function normalizeData(array $data): array
    {
        $articles = [];

        foreach ($data['response']['results'] as $article) {
            $articles[] = [
                'category' => $article['sectionName'] ?? 'General',
                'article' => $article['webTitle'] ?? '',
                'source' => 'The Guardian',
                'release_date' => date('Y-m-d', strtotime($article['webPublicationDate'])),
            ];
        }
        
        return $articles;
    }
}