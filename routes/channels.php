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
    return  (int) $user->id === (int) $id;
});

Broadcast::channel('youredu.message.{id}', function ($user, $id) {
    return  true;
});

Broadcast::channel('youredu.request.{id}', function ($user, $id) {
    return  true;
});

Broadcast::channel('youredu.school.{id}', function ($user, $id) {
    $school = getAccountObject('school',$id);
    if (is_null($school)) {
        return false;
    }

    if (in_array($user->id,getAdminIds($school))) {
        return true;
    }
    return false;
});

Broadcast::channel('youredu.class.{id}', function ($user, $id) {
    //authorize...user must be school admin,owner,facilitator,professional,laerner or parent
    //for facilitator owned, faciliators,professionals,learner,parents
    return  true;
});

Broadcast::channel('youredu.lesson.{id}', function ($user, $id) {
    return  true;
});

Broadcast::channel('youredu.chat', function ($user) {
    return  $user->id;
});
