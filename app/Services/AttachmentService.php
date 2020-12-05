<?php

namespace App\Services;

use App\Exceptions\AccountNotFoundException;
use App\Exceptions\AttachmentException;
use App\YourEdu\PostAttachment;
use Illuminate\Support\Str;
use \Debugbar;

class AttachmentService
{
    //attach an attachment from request

    public function attachmentCreate($account,$accountId,$item,$itemId,$attachable,
        $attachableId,$note,$adminId)
    {
        $mainAccount = getAccountObject($account,$accountId); 
        if (is_null($mainAccount)) {
            throw new AccountNotFoundException("{$account} not found with id {$accountId}");
        }

        $mainItem = getAccountObject($item,$itemId);
        if (is_null($mainItem)) {
            throw new AccountNotFoundException("{$item} not found with id {$itemId}");
        }

        $attach = getAccountObject($attachable, $attachableId);
        if (is_null($attach)) {
            throw new AccountNotFoundException("{$attachable} not found with id {$attachableId}");
        }

        $attachment = $this->attach($mainAccount,$mainItem,$attach,$note);

        if (is_null($attachment)) {
            throw new AttachmentException('attachment creation failed');
        }
        
        if ($adminId) {
            $admin = getAccountObject('admin',$adminId);
            if (!is_null($admin)) {
                (new ActivityTrackService())->createActivityTrack(
                    $attachment,$attachment->attachedby,$admin,__METHOD__
                );
            }
        }

        return $attachment;        
    }
    //delete an attachment from request
    public function attachmentDelete($attachmentId,$id,$adminId)
    {
        $mainAttachment = getAccountObject('postattachment',$attachmentId);
        if (is_null($mainAttachment)) {
            throw new AccountNotFoundException("attachment not found with id {$attachmentId}");
            return response()->json([
                'message' => 'unsuccessful, attachment does not exist'
            ],422);
        }

        if (($mainAttachment->attachedby->user_id && 
            $mainAttachment->attachedby->user_id !== $id) ||
            ($mainAttachment->attachedby->owner_id && 
            $mainAttachment->attachedby->owner_id !== $id) ||
            (getAccountString($mainAttachment->attachedby) === 'school' && 
            !in_array($id,getAdminIds($mainAttachment->attachedby)))) {
            throw new AttachmentException('you cannot delete attachment you did not create');
        }
        
        if ($adminId) {
            $admin = getAccountObject('admin',$adminId);
            if (!is_null($admin)) {
                (new ActivityTrackService())->createActivityTrack(
                    $mainAttachment,$mainAttachment->attachedby,$admin,__METHOD__
                );
            }
        }

        $mainAttachment->delete();

        return [
            'item' => getAccountString($mainAttachment->attachable_type),
            'itemId' => $mainAttachment->attachable_id
        ];
    }
    //attach an attachment to something(post) by an account
    public function attach($account, $attachable, $attach, $note = null)
    {
        //account is attaching attach to attachable
        $attachment = $account->attachments()->create([
            'note' => $note
        ]);

        if ($attachment) {
            $attachment->attachedwith()->associate($attach);
            $attachment->attachable()->associate($attachable);
            $attachment->save();

            $account->point->value = $account->point->value + 1;
            $account->point->save();
        }

        return $attachment;
    }

    //create an attachment
    public function createAttachment($account,$accountId,$type,$name,$description,$rationale,$aliases)
    {
        $mainAccount = getAccountObject($account,$accountId);
        if (is_null($mainAccount)) {
            throw new AccountNotFoundException("{$account} not found with id {$accountId}");
        }

        if ($type !== 'subject' && $type !== 'grade' && $type !== 'program' &&
            $type !== 'course' && $type !== 'extracurriculum') {
            throw new AttachmentException('not a valid attachment type');
        }

        $attachment = PostAttachment::create([
            'name' => $name,
            'description' => $description,
            'rationale' => $rationale,
        ]);
        if (is_null($attachment)) {
            throw new AttachmentException('attachment creation failed');
        }  
        
        $attachment->attachedby->associate($mainAccount);
        $attachment->save();

        if (!is_null($aliases)) {
            foreach ($aliases as $aliasName) {
                if (Str::length($aliasName)) {

                    $alias = $this->createAlias($mainAccount, $aliasName);

                    $alias->aliasable()->associate($attachment);
                    $alias->save();
                }
            }
        }

        $mainAccount->point->value += 1;
        $mainAccount->point->save();

        return $attachment;
    }

    //create an alias of an attachment
    public function createAttachmentAlias($account,$attachable,$name,$description)
    {
        $aliasCheck = $attachable->aliases()->where('name',$name)->count();
        if ($aliasCheck) {
            return null;
        }

        $alias = $this->createAlias($account, $name, $description);

        $alias->aliasable()->associate($attachable);
        $alias->save();

        if (is_null($alias)) {
            throw new AttachmentException('alias was not created');
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