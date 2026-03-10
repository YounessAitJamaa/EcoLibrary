<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $books = Book::with('category')->get();

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
            'category_id'  => ['required', 'exists:categories,id'],
            'title'         => ['required', 'string', 'max:255'],
            'author'        => ['required', 'string', 'max:255'],
            'slug'          => ['required', 'string', 'unique:books,slug'],
            'description'   => ['nullable', 'string'],
        ]);

        $book = Book::create([
            'category_id'  => $validate['category_id'],
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        //
    }
}
