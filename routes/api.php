<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('/login', 'Api\AuthController@login');
Route::post('/register', 'Api\AuthController@register');
Route::get('/testing', 'Api\AuthController@test');
Route::get('/profile/{account}/{accountId}', 'Api\YourEduController@profileGet');
Route::get('/post/{post}', 'Api\YourEduController@postGet');
Route::get('/post/{account}/{accountId}', 'Api\YourEduController@postsGet');


// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return response()->json([
//         'user'=> $request->user()
//         ]);
// });

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('/user', 'Api\AuthController@getUser');
    Route::post('/user/{user}/edit', 'Api\AuthController@editUser');
    Route::get('/secret', 'Api\AuthController@getSecretQuestions');
    Route::post('/secret', 'Api\AuthController@postSecretQuestions');
    Route::post('/create', 'Api\YourEduController@create');
    Route::post('/profile/{profile}/update', 'Api\YourEduController@profileUpdate')->middleware('ownaccount');
    Route::post('/post', 'Api\YourEduController@postCreate');
    Route::post('/post/{post}', 'Api\YourEduController@postEdit')->middleware('ownpost');
    
}); 