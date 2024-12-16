<?php

namespace App\Services\Normalizers;

class NormalizerFactory
{
    public function getArticleSource(string $source): ArticleNormalizerInterface
    {
        return match ($source) {
            'the_guardian' => new TheGuardianNormalizer(),
            'news_api' => new NewsApiNormalizer(),
            'new_york_times' => new NewYorkTimesNormalizer(),
            default => throw new \InvalidArgumentException("Unknown source: {$source}"),
        };
    }
}