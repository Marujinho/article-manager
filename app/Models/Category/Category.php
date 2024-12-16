<?php

namespace App\Models\Category;

use Illuminate\Database\Eloquent\Model;
use App\Models\Preference\Preference;

class Category extends Model
{
    protected $fillable = [
        'name',
    ];

    public function preferences()
    {
        return $this->hasMany(Preference::class, 'category_id');
    }
}
