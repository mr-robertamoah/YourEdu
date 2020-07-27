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
Route::get('/profile/{account}/{accountId}', 'Api\YourEduController@profileGet')
    ->middleware(['CheckAccount']);
Route::get('/posts', 'Api\YourEduController@posts');
Route::get('/posts/{account}/{accountId}', 'Api\YourEduController@postsGet')
    ->middleware(['CheckAccount']);
Route::get('/post/{post}', 'Api\YourEduController@postGet');

Route::get('/comment/{comment}', 'Api\CommentController@commentGet');
Route::get('/{item}/{itemId}/comments/', 'Api\CommentController@commentsGet')
    ->middleware(['CheckItem']);


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


    Route::post('/profile/{profile}/update', 'Api\YourEduController@profileUpdate')
        ->middleware(['OwnAccount']);


    Route::post('/post', 'Api\YourEduController@postCreate');
    Route::post('/post/{post}/{account}/{accountId}', 'Api\YourEduController@postEdit')
        ->middleware(['CheckAccount','OwnPost']);
    Route::delete('/post/{post}/{account}/{accountId}', 'Api\YourEduController@postDelete')
        ->middleware(['CheckAccount','OwnPost']);



    
    Route::delete('/like/{like}', 'Api\LikeController@likeDelete');
    
    Route::post('/comment/{comment}', 'Api\CommentController@commentEdit')
        ->middleware(['CheckAccount','OwnComment']);
    Route::delete('/comment/{comment}', 'Api\CommentController@commentDelete')
        ->middleware(['OwnComment']);

    Route::post('/{item}/{itemId}/comment', 'Api\CommentController@commentCreate')
        ->middleware(['CheckItem']);
    Route::post('/{item}/{itemId}/like', 'Api\LikeController@likeCreate')
        ->middleware(['CheckItem']);
}); 