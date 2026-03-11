<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Book::with('category');

        if($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhereHas('category', function ($categoryQuery) use ($search) {
                        $categoryQuery->where('name', 'like', '%' . $search . '%');
                    });

            });
        }

        $books = $query->latest()->get();

        return response()->json([
            'books' => $books
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'category_id'   => ['required', 'exists:categories,id'],
            'title'         => ['required', 'string', 'max:255'],
            'author'        => ['required', 'string', 'max:255'],
            'slug'          => ['required', 'string', 'max:255', 'unique:books,slug'],
            'description'   => ['nullable', 'string'],
        ]);

        $book = Book::create([
            'category_id'   => $validate['category_id'],
            'title'         => $validate['title'],
            'author'        => $validate['author'],
            'slug'          => $validate['slug'],
            'description'   => $validate['description'] ?? null,
        ]);

        return response()->json([
            'message'   => 'Book Created Successfully',
            'book'      => $book,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        $book->load('category');

        return response()->json([
            'book' => $book
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Book $book)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Book $book)
    {
        $validate = $request->validate([
            'category_id'   => ['required', 'exists:categories,id'],
            'title'         => ['required', 'string', 'max:255'],
            'author'        => ['required', 'string', 'max:255'],
            'slug'          => ['required', 'string', 'max:255', Rule::unique('books', 'slug')->ignore($book->id)],
            'description'   => ['nullable', 'string'],
        ]);

        $book->update([
            'category_id'   => $validate['category_id'],
            'title'         => $validate['title'],
            'author'        => $validate['author'],
            'slug'          => $validate['slug'],
            'description'   => $validate['description'],
        ]);

        return response()->json([
            'message'   => 'Book updated successfully',
            'book'      => $book
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        $book->delete();

        return response()->json([
            'message' => 'Book Deleted Successfully'
        ]);
    }
}
