<?php

use App\Http\Controllers\Api\CategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/roeurn', function () {
    return 'Roeurn is smartest';
});

// API Products
Route::apiResource('products', ProductController::class)->only(['index']);


// API Categories 
Route::apiResource('categories', CategoryController::class)->only(['index','store','show','update','destroy']);
