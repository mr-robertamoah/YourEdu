<?php

namespace App\Services;

use App\Exceptions\AccountNotFoundException;
use App\Exceptions\ProgramException;
use Illuminate\Support\Str;

class ProgramService
{
    public function programCreate($account,$accountId,$name,$description,$rationale,$aliases)
    {
        if ($account === 'learner' || $account === 'parent') {
            throw new ProgramException('learner or parent can only create an alias of a subject');
        }

        $program = (new AttachmentService())->createAttachment($account,
            $accountId,'program',$name,$description,
            $rationale,$aliases);

        if (is_null($program)) {
            throw new ProgramException('program was not created');
        }

        return $program;
    }

    public function programAliasCreate($programId,$account,$accountId,$name,$description)
    {
        $mainProgram = getAccountObject('program',$programId);
        if (is_null($mainProgram)) {
            throw new AccountNotFoundException("program not found with id {$programId}");
        }
        $mainAccount = getAccountObject($account,$accountId);
        if (is_null($mainAccount)) {
            throw new AccountNotFoundException("{$account} not found with id {$accountId}");
        }

        $alias = (new AttachmentService())->createAttachmentAlias($mainAccount,$mainProgram,
            $name,$description);

        if (is_null($alias)) {
            throw new ProgramException('alias was not created');
        }

        return $mainProgram;
    }

    public function programDelete($programId,$id)
    {
        $program = getAccountObject('program',$programId);
        if (is_null($program)) {
            throw new AccountNotFoundException("program not found with id {$programId}");
        }

        if ($program->addedby->user_id !== $id) {
            throw new ProgramException('you cannot delete program you did not create');
        }

        $program->delete();

        return 'successful';
    }

    public static function programAttachItem($programId,$item,$activity)
    {
        if (is_null(
            $item->programs->where('id',$programId)->first()
        )) {
            $item->programs()->attach($programId,['activity' => Str::upper($activity)]);
            $item->save();
        }
    }

    public static function programUnattachItem($programId,$item)
    {
        if (!is_null(
            $item->programs->where('id',$programId)->first()
        )) {
            $item->programs()->detach($programId);
            $item->save();
        }
    }
}