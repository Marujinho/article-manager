<?php

namespace App\Models\Article;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'category',
        'article',
        'source',
        'release_date',
    ];

    public function scopeKeyword(Builder $query, $keyword): Builder
    {
        return $query->where(function ($q) use ($keyword) {
            $q->where('category', 'LIKE', "%$keyword%")
              ->orWhere('article', 'LIKE', "%$keyword%")
              ->orWhere('source', 'LIKE', "%$keyword%");
        });
    }

}