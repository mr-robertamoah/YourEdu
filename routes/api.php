<?php

use App\Http\Controllers\Api\AnswerController;
use App\Http\Controllers\Api\AssessmentController;
use App\Http\Controllers\Api\AttachmentController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ClassController;
use App\Http\Controllers\Api\CollaborationController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\ConversationController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\DiscussionController;
use App\Http\Controllers\Api\ExtracurriculumController;
use App\Http\Controllers\Api\FlagController;
use App\Http\Controllers\Api\FollowController;
use App\Http\Controllers\Api\GradeController;
use App\Http\Controllers\Api\LessonController;
use App\Http\Controllers\Api\LikeController;
use App\Http\Controllers\Api\MarkController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\ProgramController;
use App\Http\Controllers\Api\RequestController;
use App\Http\Controllers\Api\SaveController;
use App\Http\Controllers\Api\Search;
use App\Http\Controllers\Api\SubjectController;
use App\Http\Controllers\Api\YourEduController;
use App\Http\Controllers\TimerController;
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

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/unregister', [AuthController::class, 'unregister']);
Route::post('/authentication-failed', [AuthController::class, 'authFailed'])->name('auth-failed');
Route::get('/testing', [AuthController::class, 'test']);

Route::get('/profile/{account}/{accountId}', [ProfileController::class, 'profileGet'])
    ->middleware(['CheckAccount']);

Route::get('/items/search', [DashboardController::class, 'searchItems']);
Route::get('/assessment/{assessmentId}/work', [AssessmentController::class, 'getWork']);

Route::get('/posts', [PostController::class, 'posts']);
Route::get('/posts/{account}/{accountId}', [PostController::class, 'postsGet'])
    ->middleware(['CheckAccount']);
// Route::get('/post/{post}', [PostController::class,'postGet']);
Route::get('/{item}/{itemId}', [DashboardController::class, 'itemGet'])
    ->where('item', 'discussion|post|comment|answer|question|activity|poem|riddle|lesson')
    ->where('itemId', '[0-9]+');

Route::get('/comment/{commentId}', [CommentController::class, 'getComment']);
Route::get('/{item}/{itemId}/comments/', [CommentController::class, 'getComments'])
    ->middleware(['CheckItem']);

Route::get('/answer/{answerId}/marks', [MarkController::class, 'getAnswerMarks']);
Route::get('/answer/{answer}', [AnswerController::class, 'answerGet']);
Route::get('/{item}/{itemId}/answers/', [AnswerController::class, 'answersGet'])
    ->middleware(['CheckAnswerable']);

Route::get('/subjects', [SubjectController::class, 'subjectsGet']);
Route::get('/subjects/{search}', [SubjectController::class, 'subjectsSearch']);
Route::get('/subject/{subjectId}', [SubjectController::class, 'subjectGet']);

Route::get('/programs', [ProgramController::class, 'programsGet']);
Route::get('/programs/{search}', [ProgramController::class, 'programsSearch']);
Route::get('/program/{programId}', [ProgramController::class, 'programGet']);

Route::get('/courses', [CourseController::class, 'coursesGet']);
Route::get('/courses/{search}', [CourseController::class, 'coursesSearch']);
Route::get('/course/{courseId}', [CourseController::class, 'courseGet']);

Route::get('/grades', [GradeController::class, 'gradesGet']);
Route::get('/grades/{search}', [GradeController::class, 'gradesSearch']);
Route::get('/grade/{gradeId}', [GradeController::class, 'gradeGet']);

Route::get('/search', [Search::class, 'search']);

Route::get('/user/secret/answer', [AuthController::class, 'getUserUsingSecretAnswerPair']);

Route::group(['prefix' => 'discussion'], function () {

    Route::get('/{discussionId}/messages', [DiscussionController::class, 'getMessages']);
    Route::get('/{discussionId}/participants', [DiscussionController::class, 'getParticipants']);
});

Route::get('/assessment/{assessmentId}/participants', [AssessmentController::class, 'getParticipants']);

Route::get('dashboard/search', [DashboardController::class, 'search']);

Route::get('dashboard/{item}/{itemId}', [DashboardController::class, 'getItemDetails'])
    ->where('item', 'lesson|course|class|extracurriculum|program');
// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return response()->json([
//         'user'=> $request->user()
//         ]);
// });

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);
    
    Route::post('/class', [ClassController::class, 'createClass']);
    Route::delete('/class/{classId}', [ClassController::class, 'deleteClass']);
    Route::put('/class/{classId}', [ClassController::class, 'updateClass']);

    Route::post('/program/main', [ProgramController::class, 'createProgram']);
    Route::delete('/program/{programId}/main', [ProgramController::class, 'deleteProgram']);
    Route::put('/program/{prpgramId}/main', [ProgramController::class, 'updateProgram']);

    Route::post('/lesson', [LessonController::class, 'createLesson']);
    Route::delete('/lesson/{lessonId}', [LessonController::class, 'deleteLesson']);
    Route::put('/lesson/{lessonId}', [LessonController::class, 'updateLesson']);

    Route::group(['prefix' => "assessment"], function () {

        Route::post('/{assessmentId}/invite', [AssessmentController::class, 'inviteParticipant']);
        Route::post('', [AssessmentController::class, 'createAssessment']);
        Route::delete('/{assessmentId}', [AssessmentController::class, 'deleteAssessment']);
        Route::put('/{assessmentId}', [AssessmentController::class, 'updateAssessment']);
        Route::post('/{assessmentId}/answer', [AssessmentController::class, 'answerAssessment']);
        Route::post('/{assessmentId}/mark', [AssessmentController::class, 'markAssessment']);
        Route::post('/{assessmentId}/mark/done', [AssessmentController::class, 'doneMarkingAssessment']);
        Route::post('/{assessmentId}/answer/done', [AssessmentController::class, 'doneAnsweringAssessment']);
        Route::put('/participant/{participantId}', [AssessmentController::class, 'updateAssessmentParticipant']);
        Route::delete('/participant/{participantId}', [AssessmentController::class, 'deleteAssessmentParticipant']);
        Route::delete('/marker/{markerId}', [AssessmentController::class, 'deleteAssessmentMarker']);
        Route::post('/{assessmentId}/join', [AssessmentController::class, 'joinAssessment']);
        Route::get('/{assessmentId}/work', [AssessmentController::class, 'getAssessmentWork']);
        Route::post('/join/response', [AssessmentController::class, 'joinResponse']);
        Route::post('/invitation/response', [AssessmentController::class, 'invitationResponse']);
        Route::get('/search', [AssessmentController::class, 'assessmentSearch']);
    });

    Route::post('/timer', [TimerController::class, 'createTimer']);
    Route::put('/timer/{timerId}', [TimerController::class, 'createTimer']);

    Route::post('/collaboration/create', [CollaborationController::class, 'createCollaboration']);
    Route::post('/collaboration/delete', [CollaborationController::class, 'deleteCollaboration']);
    Route::post('/collaboration/update', [CollaborationController::class, 'updateCollaboration']);

    Route::post('/extracurriculum', [ExtracurriculumController::class, 'createExtracurriculum']);
    Route::delete('/extracurriculum/{extracurriculumId}', [ExtracurriculumController::class, 'deleteExtracurriculum']);
    Route::put('/extracurriculum/{extracurriculumId}', [ExtracurriculumController::class, 'updateExtracurriculum']);

    Route::post('/course/main', [CourseController::class, 'createCourse']);
    Route::delete('/course/{courseId}/main', [CourseController::class, 'deleteCourse']);
    Route::put('/course/{courseId}/main', [CourseController::class, 'updateCourse']);

    Route::group(['prefix' => "dashboard"], function () {

        Route::get('/activities', [DashboardController::class, 'getAccountActivities']);
        Route::get('/accounts', [DashboardController::class, 'getAccounts']);
        Route::get('/users', [DashboardController::class, 'getUsers']);
        Route::get('/admins', [DashboardController::class, 'getAdmins']);
        Route::get('/account', [DashboardController::class, 'getAccountDetails']);
        Route::get('/account/specific/items', [DashboardController::class, 'getAccountSpecificItems']);
        Route::get('/account/items', [DashboardController::class, 'getAccountItems']);
        Route::get('/item/data', [DashboardController::class, 'getSectionItemData']);
        Route::post('/school/academicyear', [DashboardController::class, 'createAcademicYear']);
        Route::post('/school/academicyearsection', [DashboardController::class, 'createAcademicYearSection']);
        Route::post('/banning', [DashboardController::class, 'banningUser']);
        Route::post('/account/attach', [DashboardController::class, 'attachAccount']);
        Route::post('/account/unattach', [DashboardController::class, 'unattachAccount']);
    });

    Route::group(['prefix' => "discussion"], function () {

        Route::post('', [DiscussionController::class, 'createDiscussion']);
        Route::delete('/{discussionId}', [DiscussionController::class, 'deleteDiscussion']);
        Route::delete('/message/{messageId}', [DiscussionController::class, 'deleteMessage']);
        Route::post('/message/updatestate', [DiscussionController::class, 'updateMessageState']);
        Route::post('/{discussionId}/message', [DiscussionController::class, 'sendMessage']);
        Route::post('/contribution/response', [DiscussionController::class, 'contributionResponse']);
        Route::get('/search', [DiscussionController::class, 'discussionSearch']);
        Route::put('/{discussionId}', [DiscussionController::class, 'updateDiscussion'])
            ->where('discussionId', '[0-9]+');
        Route::put('/participant/{participantId}', [DiscussionController::class, 'updateDiscussionParticipant']);
        Route::delete('/participant/{participantId}', [DiscussionController::class, 'deleteDiscussionParticipant']);
        Route::post('/{discussionId}/join', [DiscussionController::class, 'joinDiscussion']);
        Route::post('/join/response', [DiscussionController::class, 'joinResponse']);
        Route::post('/{discussionId}/invitation', [DiscussionController::class, 'inviteParticipant']);
        Route::post('/invitation/response', [DiscussionController::class, 'invitationResponse']);
    });

    Route::group(['prefix' => "conversation"], function () {

        Route::delete('/message/{messageId}', [ConversationController::class, 'deleteMessage']);
        Route::put('/message/{messageId}', [ConversationController::class, 'updateMessageState']);
        Route::post('/{conversationId}/message', [ConversationController::class, 'sendMessage']);
        Route::post('/{conversationId}/answer', [ConversationController::class, 'sendAnswer']);
        Route::post('/{conversationId}/mark', [ConversationController::class, 'markAnswer']);
        Route::get('/{conversationId}/messages', [ConversationController::class, 'getMessages']);
        Route::post('/{conversationId}/response', [ConversationController::class, 'createConversationResponse']);
        Route::post('/{conversationId}/block', [ConversationController::class, 'blockConversation']);
        Route::post('/{conversationId}/unblock', [ConversationController::class, 'unblockConversation']);
        Route::get('/conversations', [ConversationController::class, 'getConversations']);
        Route::post('', [ConversationController::class, 'createConversation']);
    });
    Route::get('/conversations/blocked', [ConversationController::class, 'getBlockedConversations']);
    Route::get('/conversations/pending', [ConversationController::class, 'getPendingConversations']);

    Route::get('/user/posts', [PostController::class, 'getUserPosts']);

    Route::get('/user/saved', [SaveController::class, 'userSavedGet']);
    Route::get('/user/flagged', [FlagController::class, 'userFlaggedGet']);

    Route::get('/user/requests', [RequestController::class, 'getUserRequests']);
    Route::get('/dashboard/requests', [RequestController::class, 'getAccountRequests']);
    Route::post('/request/{requestId}/message', [RequestController::class, 'sendMessage']);
    Route::get('/request/{requestId}/messages', [RequestController::class, 'getMessages']);
    Route::delete('/request/message/{messageId}', [RequestController::class, 'deleteMessage']);
    Route::get('/request/accounts/search', [RequestController::class, 'searchAccounts']);
    Route::post('/request/account/send', [RequestController::class, 'createAccountRequests']);
    Route::post('/request/account/respond', [RequestController::class, 'createAccountResponse']);
    Route::get('/request/items/search', [RequestController::class, 'searchItems']);


    Route::group(['prefix' => 'user'], function () {

        Route::get('', [AuthController::class, 'getUser']);
        Route::get('/search', [AuthController::class, 'searchUser']);

        Route::get('/notifications',  [NotificationController::class, 'getNotifications']);
        Route::post('/notifications/mark',  [NotificationController::class, 'markNotifications']);

        Route::put('', [AuthController::class, 'editUser']);
        Route::get('/secrets', [AuthController::class, 'getQuestions']);
        Route::post('/secret', [AuthController::class, 'createUserQuestion']);
        Route::delete('/secret/{questionId}', [AuthController::class, 'deleteUserQuestion']);
        Route::delete('/answer/{answerId}', [AuthController::class, 'deleteUserAnswer']);
        Route::post('/answer', [AuthController::class, 'createUserAnswer']);
        Route::post('/account', [AuthController::class, 'createAccount']);
        Route::put('/{account}/{accountId}', [AuthController::class, 'updateAccount']);
        Route::delete('/{account}/{accountId}', [AuthController::class, 'deleteAccount'])
            ->where('account', "learner|facilitator|professional|parent|school")
            ->where('accountId', "[0-9]+");
    });


    Route::post('/profile/{profile}/update', [ProfileController::class, 'profileUpdate']);
    Route::post('/profile/{profile}/addinfo', [ProfileController::class, 'profileAddInfo']);
    Route::post('/profile/{profile}/profilepic', [ProfileController::class, 'profilePicUpdate']);
    Route::post('/markinfo', [ProfileController::class, 'profileMarkInfo']);
    Route::post('/deleteinfo', [ProfileController::class, 'profileDeleteInfo']);
    Route::post('/profile/{profile}/uploadfile', [ProfileController::class, 'profileUploadFile']);

    Route::post('/post', [PostController::class, 'createPost']);
    Route::post('/post/{postId}', [PostController::class, 'updatePost'])
        ->middleware(['CheckAccount']);
    Route::delete('/post/{postId}', [PostController::class, 'deletePost'])
        ->middleware(['CheckAccount']);

    Route::delete('/like/{likeId}', [LikeController::class, 'deleteLike']);
    Route::post('/like', [LikeController::class, 'createLike']);

    Route::delete('/save/{saveId}', [SaveController::class, 'deleteSave']);
    Route::post('/save', [SaveController::class, 'createSave']);

    Route::delete('/flag/{flagId}', [FlagController::class, 'deleteFlag']);
    Route::post('/flag', [FlagController::class, 'createFlag']);

    Route::post('/{item}/{itemId}/mark', [MarkController::class, 'createMark'])
        ->where('item', 'answer');
    Route::put('/mark/{markId}', [MarkController::class, 'updateMark']);
    Route::delete('/mark/{markId}', [MarkController::class, 'deleteMark']);

    Route::post('/{media}/{mediaId}/change', [ProfileController::class, 'profileMediaChange']);
    Route::post('/{media}/{mediaId}/delete', [ProfileController::class, 'profileMediaDelete'])
        ->where('media', 'image|video|audio');

    Route::get('/{requestAccount}/{requestAccountId}/{media}/private', [ProfileController::class, 'profilePrivateMediasGet']);

    Route::put('/comment/{commentId}', [CommentController::class, 'updateComment'])
        ->middleware(['CheckAccount']);
    Route::delete('/comment/{commentId}', [CommentController::class, 'deleteComment']);
    Route::post('/comment', [CommentController::class, 'createComment']);

    Route::put('/answer/{answerId}', [AnswerController::class, 'updateAnswer']);
    Route::delete('/answer/{answerId}/', [AnswerController::class, 'deleteAnswer']);
    Route::post('/{item}/{itemId}/answer', [AnswerController::class, 'createAnswer'])
        ->middleware(['CheckItem']);

    Route::group(['prefix' => 'follow'], function () {

        Route::get('/user/followers', [FollowController::class, 'getFollowers']);
        Route::get('/user/followings', [FollowController::class, 'getFollowings']);
        Route::get('/user/requests', [FollowController::class, 'getFollowRequests']);
        Route::post('/request/decline', [FollowController::class, 'declineFollowRequest']);
        Route::post('/request/accept', [FollowController::class, 'followBack']);
        Route::post('/{otherAccount}/{otherAccountId}', [FollowController::class, 'follow'])
            ->where('otherAccount', 'learner|facilitator|professional|parent|school');
        Route::delete('/{followId}', [FollowController::class, 'unfollow']);
    });

    Route::post('/subject/create', [SubjectController::class, 'createSubjectAsAttachment']);
    Route::post('/subject/{subjectId}/alias', [SubjectController::class, 'createSubjectAttachmentAlias']);
    Route::delete('/subject/{subjectId}', [SubjectController::class, 'deleteSubjectAsAttachment']);

    Route::post('/program/create', [ProgramController::class, 'createProgramAsAttachment']);
    Route::post('/program/{programId}/alias', [ProgramController::class, 'createProgramAttachmentAlias']);
    Route::delete('/program/{programId}', [ProgramController::class, 'deleteProgramAsAttachment']);

    Route::post('/course/create', [CourseController::class, 'createCourseAsAttachment']);
    Route::post('/course/{courseId}/alias', [CourseController::class, 'createCourseAttachmentAlias']);
    Route::delete('/course/{courseId}', [CourseController::class, 'deleteCourseAsAttachment']);

    Route::post('/grade/create', [GradeController::class, 'createGradeAsAttachment']);
    Route::post('/grade/{gradeId}/alias', [GradeController::class, 'createGradeAttachmentAlias']);
    Route::delete('/grade/{gradeId}', [GradeController::class, 'deleteGradeAsAttachment']);

    Route::post('/attachment/create', [AttachmentController::class, 'createAttachment']);
    Route::delete('/attachment/{attachmentId}', [AttachmentController::class, 'deleteAttachment']);
});

Route::get('/{requestAccount}/{requestAccountId}/{media}', [ProfileController::class, 'profileMediasGet'])
    ->where('media', 'images|videos|audios')
    ->where('requestAccount', 'learner|facilitator|parent|professional|school|admin');
