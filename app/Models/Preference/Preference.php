<?php

namespace App\Models\Preference;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category\Category;
use App\Models\User\User;


class Preference extends Model
{
    
    use HasFactory;

    protected $table = 'preferences';

    protected $fillable = [
        'user_id',
        'category_id',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}