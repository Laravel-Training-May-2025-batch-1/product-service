<?php

use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('authenticated')->group(function () {
    Route::get('/', function (Request $request) {
        return response()->json([
            'message' => 'Hello, world!',
        ]);
    });
});

Route::apiResource('/products', ProductController::class)->only(['index', 'show']);
