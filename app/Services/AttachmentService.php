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
        $mainAccount = getYourEduModel($account,$accountId); 
        if (is_null($mainAccount)) {
            throw new AccountNotFoundException("{$account} not found with id {$accountId}");
        }

        $mainItem = getYourEduModel($item,$itemId);
        if (is_null($mainItem)) {
            throw new AccountNotFoundException("{$item} not found with id {$itemId}");
        }

        $attach = getYourEduModel($attachable, $attachableId);
        if (is_null($attach)) {
            throw new AccountNotFoundException("{$attachable} not found with id {$attachableId}");
        }

        $attachment = $this->attach($mainAccount,$mainItem,$attach,$note);

        if (is_null($attachment)) {
            throw new AttachmentException('attachment creation failed');
        }
        
        if ($adminId) {
            $admin = getYourEduModel('admin',$adminId);
            if (!is_null($admin)) {
                (new ActivityTrackService())->trackActivity(
                    $attachment,$attachment->attachedby,$admin,__METHOD__
                );
            }
        }

        return $attachment;        
    }
    //delete an attachment from request
    public function attachmentDelete($attachmentId,$id,$adminId)
    {
        $mainAttachment = getYourEduModel('postattachment',$attachmentId);
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
            (class_basename_lower($mainAttachment->attachedby) === 'school' && 
            !in_array($id,$mainAttachment->attachedby->getAdminIds()))) {
            throw new AttachmentException('you cannot delete attachment you did not create');
        }
        
        if ($adminId) {
            $admin = getYourEduModel('admin',$adminId);
            if (!is_null($admin)) {
                (new ActivityTrackService())->trackActivity(
                    $mainAttachment,$mainAttachment->attachedby,$admin,__METHOD__
                );
            }
        }

        $mainAttachment->delete();

        return [
            'item' => class_basename_lower($mainAttachment->attachable_type),
            'itemId' => $mainAttachment->attachable_id
        ];
    }

    /**
     * attach an attachment to something(post) by an account
     * 
    */
    public function attach($account, $attachable, $attach = null, $note = null)
    {
        if (is_null($attach)) return null;
        
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

    /**
     * attach an attachment to something(post) by an account
     * 
    */
    public function detach($attachable, $attach = null)
    {
        if (is_null($attach)) return null;
        
        $attachable->attachments()
            ->where('attachedwith_type', $attach::class)
            ->where('attachedwith_id', $attach->id)
            ->first()
            ?->delete();
    }

    //create an attachment
    public function createAttachment($account,$accountId,$type,$name,$description,$rationale,$aliases)
    {
        $mainAccount = getYourEduModel($account,$accountId);
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