<?php

namespace App\Services;

use App\Exceptions\AccountNotFoundException;
use App\Exceptions\CourseException;

class CourseService
{
    public function courseCreate($account,$accountId,$name,$description,$rationale,$aliases)
    {
        if ($account === 'learner' || $account === 'parent') {
            throw new CourseException('learner or parent can only create an alias of a subject');
        }

        $course = (new AttachmentService())->createAttachment($account,
            $accountId,'course',$name,$description,
            $rationale,$aliases);

        if (is_null($course)) {
            throw new CourseException('course was not created');
        }

        return $course;
    }

    public function courseAliasCreate($courseId,$account,$accountId,$name,$description)
    {
        $mainCourse = getAccountObject('course',$courseId);
        if (is_null($mainCourse)) {
            throw new AccountNotFoundException("course not found with id {$courseId}");
        }
        $mainAccount = getAccountObject($account,$accountId);
        if (is_null($mainAccount)) {
            throw new AccountNotFoundException("{$account} not found with id {$accountId}");
        }

        $alias = (new AttachmentService())->createAttachmentAlias($mainAccount,$mainCourse,
            $name,$description);

        if (is_null($alias)) {
            throw new CourseException('alias was not created');
        }

        return $mainCourse;
    }

    public function courseDelete($courseId,$id)
    {
        $course = getAccountObject('course',$courseId);
        if (is_null($course)) {
            throw new AccountNotFoundException("course not found with id {$courseId}");
        }

        if ($course->addedby->user_id !== $id) {
            throw new CourseException('you cannot delete course you did not create');
        }

        $course->delete();

        return 'successful';
    }
}