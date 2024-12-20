<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Movie;

// Retrieve all movies
Route::get('/movies', function () {
    return Movie::all();
});

// Add a new movie
Route::post('/movies', function (Request $request) {
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'year' => 'required|integer',
    ]);
    return Movie::create($validated);
});

// Update a movie
Route::put('/movies/{id}', function (Request $request, $id) {
    $movie = Movie::findOrFail($id);
    $movie->update($request->only(['title', 'description', 'year']));
    return $movie;
});

// Delete a movie
Route::delete('/movies/{id}', function ($id) {
    $movie = Movie::findOrFail($id);
    $movie->delete();
    return response()->json(['message' => 'Movie deleted successfully']);
});
