<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'category_id',
        'title',
        'author',
        'slug',
        'description',
        'view_count'
    ];

    protected $casts = [
        'view_count' => 'integer'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function copies()
    {
        return $this->hasMany(Copy::class);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function scopeNewArrivals($query)
    {
        return $query->latest();
    }

    public function scopeAvailable($query)
    {
        return $query->whereHas('copies', function($q) {
            $q->where('status', 'available');
        });
    }

    public function scopePopular($query)
    {
        return $query->orderBy('view_count', 'desc');
    }
}
