<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NoteController;

// Read operations - lenient rate limit (120/min)
Route::middleware(['throttle:lenient'])->group(function () {
    Route::get('/notes', [NoteController::class, 'index']);
    Route::get('/notes/{id}', [NoteController::class, 'show']);
});

// Write operations - standard rate limit (60/min)
Route::middleware(['throttle:api'])->group(function () {
    Route::post('/notes', [NoteController::class, 'store']);
    Route::put('/notes/{id}', [NoteController::class, 'update']);
    Route::delete('/notes/{id}', [NoteController::class, 'destroy']);
});