<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'categorie_id',
        'title',
        'author',
        'slug',
        'description'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
