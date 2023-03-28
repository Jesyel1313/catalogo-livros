<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use App\Helpers\Routing;

use Illuminate\Support\Facades\Route;

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

Route::prefix('v1')->group(function () {
    // Authentication.
    Route::get('auth', [AuthController::class, 'retrieve']);
    Route::post('auth', [AuthController::class, 'register']);
    Route::put('auth', [AuthController::class, 'update']);
    Route::delete('auth', [AuthController::class, 'remove']);

    // Users.
    Route::post('users', [UserController::class, 'register']);

    // Categories.
    Routing::createPersistenceRoutes('categories', CategoryController::class);
    // Authors.
    Routing::createPersistenceRoutes('authors', AuthorController::class);
    // Books.
    Routing::createPersistenceRoutes('books', BookController::class);
});