<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\MenuController;
use App\Http\Controllers\API\ItemController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\ImageController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::apiResource('menus', MenuController::class)->middleware('auth:api');

Route::group( ['middleware' => 'auth:api'], function() {
    // Menu
    Route::get('menus', [MenuController::class, 'index']);
    Route::get('menu/{id}', [MenuController::class, 'show']);
    Route::post('menu', [MenuController::class, 'store']);
    Route::patch('menu', [MenuController::class, 'update']);
    Route::delete('menu', [MenuController::class, 'destroy']);

    // Item
    Route::get('items', [ItemController::class, 'index']);
    Route::get('item/{id}', [ItemController::class, 'show']);
    Route::post('item', [ItemController::class, 'store']);
    Route::patch('item', [ItemController::class, 'update']);
    Route::delete('item', [ItemController::class, 'destroy']);
    
    // Category
    Route::get('categories', [CategoryController::class, 'index']);
    Route::get('category/{id}', [CategoryController::class, 'show']);
    Route::post('category', [CategoryController::class, 'store']);
    Route::patch('category', [CategoryController::class, 'update']);
    Route::delete('category', [CategoryController::class, 'destroy']);
    
    // Image
    Route::get('images', [ImageController::class, 'index']);
    Route::get('image/{id}', [ImageController::class, 'show']);
    Route::post('image', [ImageController::class, 'store']);
    Route::patch('image', [ImageController::class, 'update']);
    Route::delete('image', [ImageController::class, 'destroy']);
});


