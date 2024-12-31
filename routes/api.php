<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\AttachmentController;
use App\Http\Controllers\Api\CategoryController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// auth routes
Route::post('/login', [UserController::class, 'login']);
Route::post('/register', [UserController::class, 'store']);
Route::post('/logout', [UserController::class, 'logout']);

// categories routes
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{id}', [CategoryController::class, 'show']);

// posts routes 
Route::get('/posts', [PostController::class, 'index']);
Route::get('/posts/{slug}', [PostController::class, 'show']);
Route::get('/postsLastestData', [PostController::class, 'getLastestData']);

// attchments routes 
Route::get('/attachments/filterPost/{post_id}', [AttachmentController::class, 'index']);
Route::get('/attachments/{id}', [AttachmentController::class, 'show']);

// Route::middleware('auth:sanctum', 'custom.auth')->group(function () {
Route::middleware('custom.auth')->group(function () {
   Route::get('/user', function (Request $request) {
      return $request->user();
   });

   Route::get('/users', [UserController::class, 'index']);
   Route::get('/users/{id}', [UserController::class, 'show']);
   Route::put('/users/{id}', [UserController::class, 'update']);
   Route::post('/refreshToken', [UserController::class, 'refreshToken']);

   Route::post('/categories', [CategoryController::class, 'store']);
   Route::put('/categories/{id}', [CategoryController::class, 'update']);
   Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);

   Route::post('/posts', [PostController::class, 'store']);
   Route::put('/posts/{id}', [PostController::class, 'update']);
   Route::delete('/posts/{id}', [PostController::class, 'destroy']);

   Route::post('/attachments', [AttachmentController::class, 'store']);
   Route::put('/attachments/{id}', [AttachmentController::class, 'update']);
   Route::delete('/attachments/{id}', [AttachmentController::class, 'destroy']);
});
