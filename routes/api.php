<?php

use App\Http\Controllers\Api\AttachmentController;
use App\Http\Controllers\Api\GradeController;
use App\Http\Controllers\Api\Search;
use App\Http\Controllers\Api\SubjectController;
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

Route::get('/profile/{account}/{accountId}', 'Api\ProfileController@profileGet')
    ->middleware(['CheckAccount']);

// Route::get('/profile/{profile}/{requestAccount}/{requestAccountId}/{media}', 'Api\ProfileController@profileMediaGet');

Route::get('/posts', 'Api\PostController@posts');
Route::get('/posts/{account}/{accountId}', 'Api\PostController@postsGet')
->middleware(['CheckAccount']);
Route::get('/post/{post}', 'Api\PostController@postGet');

Route::get('/comment/{comment}', 'Api\CommentController@commentGet');
Route::get('/{item}/{itemId}/comments/', 'Api\CommentController@commentsGet')
->middleware(['CheckItem']);

Route::get('/answer/{answer}', 'Api\AnswerController@answerGet');
Route::get('/{item}/{itemId}/answers/', 'Api\AnswerController@answersGet')
->middleware(['CheckAnswerable']);

Route::get('/{requestAccount}/{requestAccountId}/{media}', 'Api\ProfileController@profileMediasGet');

Route::get('/subjects', [SubjectController::class,'subjectsGet']);
Route::get('/subjects/{search}', [SubjectController::class,'subjectsSearch']);
Route::get('/subjects', [SubjectController::class,'subjectsGet']);

Route::get('/grades', [GradeController::class,'gradesGet']);
Route::get('/grades/{search}', [GradeController::class,'gradesSearch']);
Route::get('/grades', [GradeController::class,'gradesGet']);

Route::get('/search', [Search::class,'search']);


// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return response()->json([
//         'user'=> $request->user()
//         ]);
// });

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('/user/posts', 'Api\PostController@getUserPosts');
    
    Route::get('/user', 'Api\AuthController@getUser');
    Route::get('/user/followrequests', 'Api\FollowController@followRequests');
    Route::post('/user/{user}/edit', 'Api\AuthController@editUser');

    Route::get('/secret', 'Api\AuthController@getSecretQuestions');
    Route::post('/secret', 'Api\AuthController@postSecretQuestions');
    Route::post('/create', 'Api\YourEduController@create');

    
    Route::post('/profile/{profile}/update', 'Api\ProfileController@profileUpdate');
    Route::post('/profile/{profile}/addinfo', 'Api\ProfileController@profileAddInfo');
    Route::post('/profile/{profile}/profilepic', 'Api\ProfileController@profilePicUpdate');
    Route::post('/markinfo', 'Api\ProfileController@profileMarkInfo');
    Route::post('/deleteinfo', 'Api\ProfileController@profileDeleteInfo');
    Route::post('/profile/{profile}/uploadfile', 'Api\ProfileController@profileUploadFile');
    
    Route::post('/post', 'Api\PostController@postCreate');
    Route::post('/post/{post}/{account}/{accountId}', 'Api\PostController@postEdit')
    ->middleware(['CheckAccount','OwnPost']);
    Route::delete('/post/{post}/{account}/{accountId}', 'Api\PostController@postDelete')
    ->middleware(['CheckAccount','OwnPost']);
    
    Route::post('/follow/{account}/{accountId}','Api\FollowController@follow')
    ->middleware(['CheckAccount']);
    Route::delete('/follow/{follow}','Api\FollowController@unfollow');
    
    Route::delete('/like/{like}', 'Api\LikeController@likeDelete');
    Route::post('/{item}/{itemId}/like', 'Api\LikeController@likeCreate')
    ->middleware(['CheckItem']);
    
    Route::delete('/save/{save}', 'Api\SaveController@saveDelete');
    Route::post('/{item}/{itemId}/save', 'Api\SaveController@saveCreate')
    ->middleware(['CheckItem']);
    
    Route::delete('/flag/{flag}', 'Api\FlagController@flagDelete');
    Route::post('/{item}/{itemId}/flag', 'Api\FlagController@flagCreate')
    ->middleware(['CheckItem']);

    Route::post('/{answer}/{answerId}/mark', 'Api\MarkController@markCreate');

    Route::post('/{media}/{mediaId}/change', 'Api\ProfileController@profileMediaChange');
    Route::post('/{media}/{mediaId}/delete', 'Api\ProfileController@profileMediaDelete');
    
    Route::post('/comment/{comment}', 'Api\CommentController@commentEdit')
    ->middleware(['CheckAccount','OwnComment']);
    Route::delete('/comment/{comment}', 'Api\CommentController@commentDelete')
    ->middleware(['OwnComment']);
    Route::post('/{item}/{itemId}/comment', 'Api\CommentController@commentCreate')
    ->middleware(['CheckItem']);

    Route::post('/answer/{answer}', 'Api\AnswerController@answerEdit')
    ->middleware(['OwnAnswer']);
    Route::delete('/answer/{answer}', 'Api\AnswerController@answerDelete')
    ->middleware(['OwnAnswer']);
    Route::post('/{item}/{itemId}/answer', 'Api\AnswerController@answerCreate')
    ->middleware(['CheckItem']);

    
    Route::get('/requests/follow', 'Api\RequestController@getFollowRequests');
    Route::post('/decline/request/{requestId}', 'Api\RequestController@declineRequest');
    Route::post('/accept/request/{requestId}', 'Api\FollowController@followBack');
    
    Route::get('/{requestAccount}/{requestAccountId}/{media}/private', 'Api\ProfileController@profilePrivateMediasGet');
    
    Route::post('/subject/create', [SubjectController::class,'subjectCreate']);
    Route::post('/subject/{subject}/alias', [SubjectController::class,'subjectAliasCreate']);
    Route::delete('/subject/{subject}', [SubjectController::class,'subjectDelete']);

    Route::post('/grade/create', [GradeController::class,'gradeCreate']);
    Route::post('/grade/{grade}/alias', [GradeController::class,'gradeAliasCreate']);
    Route::delete('/grade/{grade}', [GradeController::class,'gradeDelete']);

    Route::post('/attachment/create', [AttachmentController::class,'attachmentCreate']);
    Route::delete('/attachment/{attachment}', [AttachmentController::class,'attachmentDelete']);
}); 