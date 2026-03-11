<?php

namespace App\Http\Controllers;

use App\Models\Copy;
use Illuminate\Http\Request;

use function Symfony\Component\Clock\now;

class CopyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $copies = Copy::with('book')->get();

        return response()->json([
            'copies' => $copies
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
        $validated = $request->validate([
            'book_id' => ['required', 'exists:books,id'],  
            'status' => ['required', 'in:available,borrowed,degraded'],
        ]);

        $copy = Copy::create([
            'book_id' => $validated['book_id'],
            'status' => $validated['status'],
            'degraded_at' => $validated['status'] === 'degraded' ? now() : null,
        ]);

        $copy->load('book');

        return response()->json([
            'message' => 'Copy Created successfully',
            'copy' => $copy,
        ], 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(Copy $copy)
    {
        $copy->load('book');

        return response()->json([
            'copy' => $copy
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Copy $copy)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Copy $copy)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:available,borrowed,degraded'],
        ]);

        $copy->update([
            'status' => $validated['status'],
            'degraded_at' => $validated['status'] === 'degraded' ? now() : null,
        ]);

        $copy->load('book');

        return response()->json([
            'message' => 'copy updated successfully',
            'copy' => $copy
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Copy $copy)
    {
        //
    }
}
