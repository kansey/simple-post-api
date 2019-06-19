<?php

use Illuminate\Http\Request;
use App\Http\Controllers\API\PostController;

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

Route::middleware('api')->post('/create', 'API\PostController@create');
Route::middleware('api')->get('/ip', 'API\PostController@ip');
Route::middleware('api')->post('/rating', 'API\PostController@rating');
Route::middleware('api')->post('/posts', 'API\PostController@posts');
