<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Article\Article;

class ArticleFactory extends Factory
{
    protected $model = Article::class;

    public function definition()
    {
        return [
            'category' => $this->faker->word, 
            'article' => $this->faker->word,
            'source' => $this->faker->word,
            'release_date' => $this->faker->date,
        ];
    }
}