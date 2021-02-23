<?php

namespace App\Services;

use App\Exceptions\AccountNotFoundException;
use App\Exceptions\SubjectException;
use App\YourEdu\Subject;
use Illuminate\Support\Str;

class SubjectService
{
    public function subjectCreate($account,$accountId,$name,$description,$rationale,$aliases)
    {
        if ($account === 'learner' || $account === 'parent') {
            throw new SubjectException('learner or parent can only create an alias of a subject');
        }

        $subject = (new AttachmentService())->createAttachment($account,
            $accountId,'subject',$name,$description,
            $rationale,$aliases);

        if (is_null($subject)) {
            throw new SubjectException('subject was not created');
        }

        return $subject;
    }

    public function subjectAliasCreate($subjectId,$account,$accountId,$name,$description)
    {
        $mainSubject = getYourEduModel('subject',$subjectId);
        if (is_null($mainSubject)) {
            throw new AccountNotFoundException("subject not found with id {$subjectId}");
        }
        $mainAccount = getYourEduModel($account,$accountId);
        if (is_null($mainAccount)) {
            throw new AccountNotFoundException("{$account} not found with id {$accountId}");
        }

        $alias = (new AttachmentService())->createAttachmentAlias($mainAccount,$mainSubject,
            $name,$description);

        if (!$alias) {
            throw new SubjectException('alias was not created');
        }

        return $mainSubject;
    }

    public function subjectDelete($subjectId,$id)
    {
        $subject = getYourEduModel('subject',$subjectId);
        if (is_null($subject)) {
            throw new AccountNotFoundException("subject not found with id {$subjectId}");
        }

        if ($subject->addedby->user_id !== $id) {
            throw new SubjectException('you cannot delete subject you did not create');
        }

        $subject->delete();

        return 'successful';
    }

    public static function subjectAttachItem($subjectId,$item,$activity)
    {
        if (is_null(
            $item->subjects->where('id',$subjectId)->first()
        )) {
            $pivotArray = is_string($activity) ? ['activity' => Str::upper($activity)] : [];
            $item->subjects()->attach($subjectId,$pivotArray);
            $item->save();
        }
    }

    public static function subjectUnattachItem($subjectId,$item)
    {
        if (!is_null(
            $item->subjects->where('id',$subjectId)->first()
        )) {
            $item->subjects()->detach($subjectId);
            $item->save();
        }
    }

    public static function getSubjects()
    {
        return Subject::all();
    }
}