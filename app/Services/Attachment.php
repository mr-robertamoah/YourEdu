<?php

namespace App\Services;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use \Debugbar;

class Attachment extends Controller
{
    //attach an attachment to something(post) by an account
    public static function attach($account, $attachable, $attachwith, $attachwithId, $note = null)
    {
        $attach = getAccountObject($attachwith, $attachwithId);

        if (is_null($attach) || is_null($attachable) || is_null($account)) {
            return null;
        }

        $attachment = $account->attachments()->create([
            'note' => $note
        ]);

        if ($attachment) {
            $attachment->attachedwith()->associate($attach);
            $attachment->attachable()->associate($attachable);
            $attachment->save();

            $account->point->value += 1;
            $account->point->save();
        }

        return $attachment;
    }

    //create an attachment
    public function createAttachment($request,$type)
    {
        $account = getAccountObject($request->account,$request->accountId);

        if (is_null($account)) {
            Debugbar::info('account is null');
            return null;
        }

        $attachment = null;

        if ($type === 'subject') {
            
            $attachment = $account->uniqueSubjectsAdded()->create([
                'name' => $request->name,
                'description' => $request->description,
                'rationale' => $request->rationale,
            ]);
        } else if ($type === 'grade') {
            
            $attachment = $account->uniqueGradetsAdded()->create([
                'name' => $request->name,
                'description' => $request->description,
                'rationale' => $request->rationale,
            ]);
        } else if ($type === 'program') {
            
            $attachment = $account->uniqueProgramsAdded()->create([
                'name' => $request->name,
                'description' => $request->description,
                'rationale' => $request->rationale,
            ]);
        } else if ($type === 'course') {
            
            $attachment = $account->uniqueCoursesAdded()->create([
                'name' => $request->name,
                'description' => $request->description,
                'rationale' => $request->rationale,
            ]);
        } else {
            Debugbar::info('not a valid attachment type');
            return null;
        }

        if (!$attachment) {
            Debugbar::info('attachment is null');
            return null;
        }

        if ($request->has('aliases')) {
            foreach ($request->aliases as $aliasName) {
                if (Str::length($aliasName)) {

                    $alias = $this->createAlias($account, $aliasName);

                    $alias->aliasable()->associate($attachment);
                    $alias->save();
                }
            }
        }

        $account->point->value += 1;
        $account->point->save();

        return $attachment;
    }

    //create an alias of an attachment
    public function createAttachmentAlias($request,$program)
    {
        $account = getAccountObject($request->account, $request->accountId);

        if (is_null($account)) {
            return null;
        }

        $aliasCheck = $program->aliases()->where('name',$request->name)->count();
        if ($aliasCheck) {
            return null;
        }

        $alias = $this->createAlias($account, $request->name, $request->description);

        $alias->aliasable()->associate($program);
        $alias->save();

        if (!$alias) {
            return null;
        }

        $account->point->value += 1;
        $account->point->save();

        return $alias;
    }

    private function createAlias($account, $name, $description = null)
    {
        $alias = $account->aliasesAdded()->create([
            'name' => $name,
            'description' => $description,
        ]);

        return $alias;
    }
}


?>