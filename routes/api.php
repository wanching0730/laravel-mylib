<?php

use Illuminate\Http\Request;

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

//Route::get('authors', 'AuthorController@index');

Route::apiResource('authors', 'AuthorController');
Route::apiResource('books', 'BookController');
Route::apiResource('publishers', 'PublisherController');

// AuthController inside namespace Auth
// all api route has prefix 'auth': /api/auth/login
Route::middleware('api')->namespace('Auth')->prefix('auth')->group(function() { 
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me'); 
});

Route::middleware('jwt.auth')->group(function() {
    Route::apiResource('authors', 'AuthorController');
    Route::apiResource('publishers', 'PublisherController');
    Route::apiResource('books', 'BookController');
});

Route::middleware(['jwt.auth', 'can:manage-users'])->group(function() {
    // Routes for managing users (not developed in the practical exercise)
});

Route::middleware(['jwt.auth', 'can:manage-books'])->group(function() {

    Route::apiResource('authors', 'AuthorController')->only([
        'store',
        'update',
    ]);
    Route::apiResource('publishers', 'PublisherController')->only([
        'store',
        'update',
    ]);
    Route::apiResource('books', 'BookController')->only([
        'store',
        'update',
    ]);

});


Route::middleware(['jwt.auth', 'can:view-books'])->group(function() {

    Route::apiResource('authors', 'AuthorController')->only([
        'index',
        'show',
    ]);
    Route::apiResource('publishers', 'PublisherController')->only([
        'index',
        'show',
    ]);
    Route::apiResource('books', 'BookController')->only([
        'index',
        'show',
    ]);

});
