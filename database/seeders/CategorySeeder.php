<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'All', 
            'Animated Films', 
            'Australia news', 
            'Cooking and Cookbooks', 
            'Eric Adams Federal Corruption Case (24 CR 556)', 
            'Football', 
            'General', 
            'International Relations', 
            'Israel-Gaza War (2023- )', 
            'Libel and Slander', 
            'News', 
            'Politics', 
            'Presidential Transition (US)', 
            'Restaurants', 
            'Sport', 
            'Television', 
            'UK news', 
            'US news'
        ];

        foreach ($categories as $category) {
            Category::create(['name' => $category]);
        }
    }
}
