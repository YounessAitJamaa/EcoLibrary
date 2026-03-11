<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use App\Models\Copy;
use Illuminate\Http\Request;

class StatsController extends Controller
{
    public function index()
    {
        $totalBooks = Book::count();
        $totalCategories = Category::count();
        $totalCopies = Copy::count();

        $degradeCount = Copy::where('status', 'degraded')->count();
        $globalHealth = $totalCopies > 0 ? round((($totalCopies - $degradeCount) / $totalCopies) * 100, 2) : 100;

        $mostViewed = Book::popular()->take(1)->get();

        $booksDecline = Book::withCount(['copies' => fn($q) => $q->where('status', 'degraded')])
                            ->having('copies_count', '>', 0)
                            ->get();


        return response()->json([
            'stats' => [
                'total_books' => $totalBooks,
                'total_categories' => $totalCategories,
                'total_copies' => $totalCopies,
                'global_health_percentage' => $globalHealth . '%',
            ],
            'most_viewed_books' => $mostViewed,
            'degraded_books_report' => $booksDecline
        ]);
    }
}
