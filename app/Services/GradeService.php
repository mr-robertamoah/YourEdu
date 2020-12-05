<?php

namespace App\Services;

use App\Exceptions\AccountNotFoundException;
use App\Exceptions\GradeException;

class GradeService
{
    public function gradeCreate($account,$accountId,$name,$description,$rationale,$aliases)
    {
        if ($account === 'learner' || $account === 'parent') {
            throw new GradeException('learner or parent can only create an alias of a subject');
        }

        $grade = (new AttachmentService())->createAttachment($account,
            $accountId,'grade',$name,$description,
            $rationale,$aliases);

        if (is_null($grade)) {
            throw new GradeException('grade was not created');
        }

        return $grade;

    }

    public function gradeAliasCreate($gradeId,$account,$accountId,$name,$description)
    {
        $mainGrade = getAccountObject('grade',$gradeId);
        if (is_null($mainGrade)) {
            throw new AccountNotFoundException("grade not found with id {$gradeId}");
        }
        $mainAccount = getAccountObject($account,$accountId);
        if (is_null($mainAccount)) {
            throw new AccountNotFoundException("{$account} not found with id {$accountId}");
        }

        $alias = (new AttachmentService())->createAttachmentAlias($mainAccount,$mainGrade,
            $name,$description);

        if (!$alias) {
            throw new GradeException('alias was not created');
        }

        return $mainGrade;
    }

    public function gradeDelete($gradeId,$id)
    {
        $grade = getAccountObject('grade',$gradeId);
        if (is_null($grade)) {
            throw new AccountNotFoundException("grade not found with id {$gradeId}");
        }

        if ($grade->addedby->user_id !== $id) {
            throw new GradeException('you cannot delete grade you did not create');
        }

        $grade->delete();

        return 'successful';
    }

    public static function gradeAttachItem($gradeId,$item)
    {
        if (is_null(
            $item->grades->where('id',$gradeId)->first()
        )) {
            $item->grades()->attach($gradeId);
            $item->save();
        }
    }
}