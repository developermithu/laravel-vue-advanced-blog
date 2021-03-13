<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});


Route::get('/all-tags', [AdminController::class, 'index']);
Route::post('/create-tag', [AdminController::class, 'addTag']);
Route::post('/edit/tag', [AdminController::class, 'editTag']);
Route::post('/delete/tag', [AdminController::class, 'destroy']);

Route::post('/category/upload-image', [AdminController::class, 'uploadImage']);
Route::post('/category/delete-image', [AdminController::class, 'deleteImage']);

// Category
Route::get('/categories', [CategoryController::class, 'index']);
Route::post('/category/create', [CategoryController::class, 'create']);
Route::post('/category/edit', [CategoryController::class, 'update']);


Route::get('/login', function () {
    return view('login');
});

// Sobar niche thakbe
Route::any('{slug}', function () {
    return view('welcome');
});
