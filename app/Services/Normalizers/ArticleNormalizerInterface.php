<?php 


namespace App\Services\Normalizers;

interface ArticleNormalizerInterface
{
    public function normalizeData(array $data): array;
    public function handleNewArticles():array;
}