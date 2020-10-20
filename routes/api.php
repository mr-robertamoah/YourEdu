<?php

use App\Http\Controllers\Api\AnswerController;
use App\Http\Controllers\Api\AttachmentController;
use App\Http\Controllers\Api\ConversationController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\DiscussionController;
use App\Http\Controllers\Api\FlagController;
use App\Http\Controllers\Api\FollowController;
use App\Http\Controllers\Api\GradeController;
use App\Http\Controllers\Api\MarkController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\ProgramController;
use App\Http\Controllers\Api\RequestController;
use App\Http\Controllers\Api\SaveController;
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

Route::get('/answer/{answerId}/marks', [MarkController::class,'getAnswerMarks']);
Route::get('/answer/{answer}', [AnswerController::class,'answerGet']);
Route::get('/{item}/{itemId}/answers/', [AnswerController::class,'answersGet'])
->middleware(['CheckAnswerable']);

Route::get('/subjects', [SubjectController::class,'subjectsGet']);
Route::get('/subjects/{search}', [SubjectController::class,'subjectsSearch']);
Route::get('/subject/{subjectId}', [SubjectController::class,'subjectGet']);

Route::get('/programs', [ProgramController::class,'programsGet']);
Route::get('/programs/{search}', [ProgramController::class,'programsSearch']);
Route::get('/program/{programId}', [ProgramController::class,'programGet']);

Route::get('/courses', [CourseController::class,'coursesGet']);
Route::get('/courses/{search}', [CourseController::class,'coursesSearch']);
Route::get('/course/{courseId}', [CourseController::class,'courseGet']);

Route::get('/grades', [GradeController::class,'gradesGet']);
Route::get('/grades/{search}', [GradeController::class,'gradesSearch']);
Route::get('/grade/{gradeId}', [GradeController::class,'gradeGet']);

Route::get('/search', [Search::class,'search']);

Route::get('/discussion/{discussionId}/messages', [DiscussionController::class,'getMessages']);
Route::get('/discussion/{discussionId}/participants', [DiscussionController::class,'getParticipants']);


// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return response()->json([
//         'user'=> $request->user()
//         ]);
// });

Route::group(['middleware' => 'auth:api'], function () {

    Route::post('/discussion', [DiscussionController::class,'createDiscussion']);
    Route::delete('/discussion/{discussionId}', [DiscussionController::class,'deleteDiscussion']);
    Route::post('/discussion/message/delete', [DiscussionController::class,'deleteMessage']);
    Route::post('/discussion/message/updatestate', [DiscussionController::class,'updateMessageState']);
    Route::post('/discussion/participant/update', [DiscussionController::class,'updateParticipantState']);
    Route::post('/discussion/participant/delete', [DiscussionController::class,'deleteDiscussionParticipant']);
    Route::post('/discussion/{discussionId}/message', [DiscussionController::class,'sendMessage']);
    Route::post('/discussion/{discussionId}/join', [DiscussionController::class,'joinDiscussion']);
    Route::post('/discussion/contribution/response', [DiscussionController::class,'contributionResponse']);
    Route::post('/discussion/join/response', [DiscussionController::class,'joinResponse']);
    Route::post('/discussion/invitation', [DiscussionController::class,'inviteParticipant']);
    Route::get('/discussion/search', [DiscussionController::class,'discussionSearch']);
    Route::post('/discussion/invitation/response', [DiscussionController::class,'invitationResponse']);
    Route::post('/discussion/{discussionId}/update', [DiscussionController::class,'updateDiscussion'])
        ->where('discussionId','[0-9]+');

    Route::post('/conversation/item/deleteitem', [ConversationController::class,'deleteItem']);
    Route::post('/conversation/item/updatestate', [ConversationController::class,'updateItemState']);
    Route::post('/conversation/{conversationId}/message', [ConversationController::class,'sendMessage']);
    Route::post('/conversation/{conversationId}/question', [ConversationController::class,'sendQuestion']);
    Route::post('/conversation/{conversationId}/answer', [ConversationController::class,'sendAnswer']);
    Route::post('/conversation/{conversationId}/markanswer', [ConversationController::class,'markAnswer']);
    Route::get('/conversation/{conversationId}/messages', [ConversationController::class,'getMessages']);
    Route::post('/conversation/{conversationId}/response', [ConversationController::class,'createConversationResponse']);
    Route::post('/conversation/{conversationId}/block', [ConversationController::class,'blockConversation']);
    Route::post('/conversation/{conversationId}/unblock', [ConversationController::class,'unblockConversation']);
    Route::get('/conversations', [ConversationController::class,'getConversations']);
    Route::get('/conversations/blocked', [ConversationController::class,'getBlockedConversations']);
    Route::get('/conversations/pending', [ConversationController::class,'getPendingConversations']);
    Route::post('/conversation', [ConversationController::class,'createConversation']);

    Route::get('/user/posts', 'Api\PostController@getUserPosts');

    Route::get('/user/saved', [SaveController::class,'userSavedGet']);
    Route::get('/user/flagged', [FlagController::class,'userFlaggedGet']);
    
    Route::get('/user', 'Api\AuthController@getUser');
    Route::get('/user/requests', [RequestController::class,'getUserRequests']);

    Route::get('/user/notifications',  [NotificationController::class,'getNotifications']);
    Route::post('/user/notifications/mark',  [NotificationController::class,'markNotifications']);
    
    Route::post('/user/{user}/edit', 'Api\AuthController@editUser');
    Route::get('/secret', 'Api\AuthController@getSecretQuestions');
    Route::post('/secret', 'Api\AuthController@postSecretQuestions');
    Route::post('/create', 'Api\YourEduController@create');

    
    Route::post('/profile/{profile}/update', [ProfileController::class,'profileUpdate']);
    Route::post('/profile/{profile}/addinfo', [ProfileController::class,'profileAddInfo']);
    Route::post('/profile/{profile}/profilepic', [ProfileController::class,'profilePicUpdate']);
    Route::post('/markinfo', [ProfileController::class,'profileMarkInfo']);
    Route::post('/deleteinfo', [ProfileController::class,'profileDeleteInfo']);
    Route::post('/profile/{profile}/uploadfile', [ProfileController::class,'profileUploadFile']);
    
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

    
    Route::get('/requests/follow', [FollowController::class,'getFollowRequests']);
    Route::post('/request/decline', [FollowController::class,'declineRequest']);
    Route::post('/request/accept', [FollowController::class,'followBack']);
    
    Route::get('/{requestAccount}/{requestAccountId}/{media}/private', 'Api\ProfileController@profilePrivateMediasGet');
    
    Route::post('/subject/create', [SubjectController::class,'subjectCreate']);
    Route::post('/subject/{subject}/alias', [SubjectController::class,'subjectAliasCreate']);
    Route::delete('/subject/{subject}', [SubjectController::class,'subjectDelete']);
    
    Route::post('/program/create', [ProgramController::class,'programCreate']);
    Route::post('/program/{program}/alias', [ProgramController::class,'programAliasCreate']);
    Route::delete('/program/{program}', [ProgramController::class,'programDelete']);
    
    Route::post('/course/create', [CourseController::class,'courseCreate']);
    Route::post('/course/{course}/alias', [CourseController::class,'courseAliasCreate']);
    Route::delete('/course/{course}', [CourseController::class,'courseDelete']);

    Route::post('/grade/create', [GradeController::class,'gradeCreate']);
    Route::post('/grade/{grade}/alias', [GradeController::class,'gradeAliasCreate']);
    Route::delete('/grade/{grade}', [GradeController::class,'gradeDelete']);

    Route::post('/attachment/create', [AttachmentController::class,'attachmentCreate']);
    Route::delete('/attachment/{attachment}', [AttachmentController::class,'attachmentDelete']);

    Route::get('/user/followers', [FollowController::class,'getFollowers']);
    Route::get('/user/followings', [FollowController::class,'getFollowings']);

}); 

Route::get('/{requestAccount}/{requestAccountId}/{media}', 'Api\ProfileController@profileMediasGet')
    ->where('requestAccount', 'learner|facilitator|parent|professional|school|admin');