<?php

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/


Broadcast::channel('App.User.{id}', function ($user, $id) {
    return $user->id === (int) $id ? $user : null;
});

Broadcast::channel('youredu.user.{id}', function ($user, $id) {
    return  $user->id === (int) $id ? $user : null;
});

Broadcast::channel('youredu.message.{id}', function ($user, $id) {
    return  true;
});

Broadcast::channel('youredu.request.{id}', function ($user, $id) {
    return  true;
});

Broadcast::channel('youredu.school.{id}', function ($user, $id) {
    $school = getYourEduModel('school', $id);
    if (is_null($school)) {
        return false;
    }
    //add ids of learners, facilitators, professionals, parents of learners
    if (in_array($user->id, $school->getAdminIds())) {
        return true;
    }
    return false;
});

Broadcast::channel('youredu.class.{id}', function ($user, $id) {
    $class = getYourEduModel('class', $id);
    if (is_null($class)) {
        return false;
    }
    //authorize...user must be school admin,owner,facilitator,professional,laerner or parent
    //for facilitator owned, faciliators,professionals,learner,parents
    if (in_array($user->id, $class->getAuthorizedUserIds())) {
        return true;
    }
    return  false;
});

Broadcast::channel('youredu.course.{id}', function ($user, $id) {
    $course = getYourEduModel('course', $id);
    if (is_null($course)) {
        return false;
    }
    //authorize...user must be school admin,owner,facilitator,professional,laerner or parent
    //for facilitator owned, faciliators,professionals,learner,parents
    if (in_array($user->id, $course->getAuthorizedUserIds())) {
        return true;
    }
    return false;
});

Broadcast::channel('youredu.lesson.{id}', function ($user, $id) {
    $lesson = getYourEduModel('lesson', $id);
    if (is_null($lesson)) {
        return false;
    }
    if (in_array($user->id, $lesson->getAuthorizedUserIds())) {
        return true;
    }
    return false;
});

Broadcast::channel('youredu.extracurriculum.{id}', function ($user, $id) {
    $extracurriculum = getYourEduModel('extracurriculum', $id);
    if (is_null($extracurriculum)) {
        return false;
    }
    if (in_array($user->id, $extracurriculum->getAuthorizedUserIds())) {
        return true;
    }
    return false;
});

Broadcast::channel('youredu.chat', function ($user) {
    return  $user->id;
});

Broadcast::channel('youredu.followers.{account}.{accountId}', function ($user, $account, $accountId) {

    if (getYourEduModel($account, $accountId)?->isFollowedbyUser($user->id)) {
        return true;
    }

    return false;
});

Broadcast::channel('youredu.followings.{account}.{accountId}', function ($user, $account, $accountId) {

    if (getYourEduModel($account, $accountId)?->isFollowableUser($user->id)) {
        return true;
    }

    return false;
});
