<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthorController;
use App\Http\Controllers\Api\V1\BookController;
use App\Http\Controllers\Api\V1\AuthController;
use Illuminate\Http\Request;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->prefix('v1')->group(function () {  

    Route::post('/logout', [AuthController::class, 'logout']);
    
    Route::apiResource('authors', AuthorController::class);
    Route::apiResource('books', BookController::class);
});


Route::fallback(function (Request $request) {
    return response()->json([
        'error' => 'The requested route was not found.',
        'path' => $request->path()
    ], 404);
});

