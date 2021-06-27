<?php

namespace App\Services;

use App\DTOs\ActivityTrackDTO;
use App\DTOs\AttachmentDTO;
use App\Events\DeleteAttachment;
use App\Events\NewAttachment;
use App\Exceptions\AccountNotFoundException;
use App\Exceptions\AttachmentException;
use App\Traits\ServiceTrait;
use App\YourEdu\PostAttachment;
use Illuminate\Support\Str;
use \Debugbar;

class AttachmentService
{
    use ServiceTrait;

    private $attachmentTypes = [
        'subject', 'grade', 'program', 'course', 'extracurriculum'
    ];

    private function trackSchoolAdmin($attachmentDTO)
    {
        if (is_null($attachmentDTO->adminId)) {
            return;
        }

        (new ActivityTrackService)->trackActivity(
            ActivityTrackDTO::createFromData(
                activityfor: $attachmentDTO->attachment->attachedby,
                performedby: $this->getModel('admin', $attachmentDTO->adminId),
                action: $attachmentDTO->method,
                activity: $attachmentDTO->attachment
            )
        );
    }

    public function deleteAttachment(AttachmentDTO $attachmentDTO)
    {
        $attachment = $this->getModel('postattachment', $attachmentDTO->attachmentId);

        $attachmentDTO = $attachmentDTO->withAttachment($attachment);

        $this->ensureCanDelete($attachmentDTO);

        $attachmentDTO->method = __METHOD__;

        $this->trackSchoolAdmin($attachmentDTO);

        $attachment->delete();

        $attachmentDTO->methodType = 'deleted';

        $attachmentDTO = $this->prepareForBroadcast($attachmentDTO);

        $this->broadcastAttachment($attachmentDTO);
    }

    private function ensureCanDelete($attachmentDTO)
    {
        if ($attachmentDTO->attachment->attachedby->user_id == $attachmentDTO->userId) {
            return;
        }

        if ((class_basename_lower($attachmentDTO->attachment->attachedby) === 'school' &&
            !in_array($attachmentDTO->userId, $attachmentDTO->attachment->attachedby->getAdminIds()))) {
            return;
        }

        $this->throwAttachmentException(
            message: 'sorry 😞, you cannot delete attachment you did not create',
            data: $attachmentDTO
        );
    }

    /**
     * attach an attachment to something(post) by an account
     * 
     */
    public function attach(AttachmentDTO $attachmentDTO)
    {
        if (is_null($attachmentDTO->attachedwith)) {
            return null;
        }

        return $this->createAttachmentWithSetData($attachmentDTO);
    }

    /**
     * detach an item from an attachable
     * 
     */
    public function detach(AttachmentDTO $attachmentDTO)
    {
        if (is_null($attachmentDTO->attachedwith)) {
            return null;
        }

        $attachmentDTO->attachable->attachments()
            ->where('attachedwith_type', $attachmentDTO->attachedwith::class)
            ->where('attachedwith_id', $attachmentDTO->attachedwith->id)
            ->first()
            ?->delete();
    }

    private function setAddedby($attachmentDTO)
    {
        if ($attachmentDTO->addedby) {
            return $attachmentDTO;
        }

        return $attachmentDTO->withAddedby(
            $this->getModel($attachmentDTO->account, $attachmentDTO->accountId)
        );
    }

    private function setAttachable($attachmentDTO)
    {
        if ($attachmentDTO->attachable) {
            return $attachmentDTO;
        }

        return $attachmentDTO->withAttachable(
            $this->getModel($attachmentDTO->item, $attachmentDTO->itemId)
        );
    }

    private function setAttachedwith($attachmentDTO)
    {
        if ($attachmentDTO->attachedwith) {
            return $attachmentDTO;
        }

        return $attachmentDTO->withAttachedwith(
            $this->getModel($attachmentDTO->type, $attachmentDTO->typeId)
        );
    }


    public function createAttachment(AttachmentDTO $attachmentDTO)
    {
        $attachmentDTO = $this->setAddedby($attachmentDTO);

        $attachmentDTO = $this->setAttachable($attachmentDTO);

        $attachmentDTO = $this->setAttachedwith($attachmentDTO);

        $attachment = $this->createAttachmentWithSetData($attachmentDTO);

        $attachmentDTO->method = __METHOD__;

        $this->trackSchoolAdmin($attachmentDTO);

        $attachmentDTO->methodType = 'created';

        $attachmentDTO = $this->prepareForBroadcast($attachmentDTO);

        $this->broadcastAttachment($attachmentDTO->withAttachment($attachment));

        return $attachment->refresh();
    }

    private function prepareForBroadcast($attachmentDTO)
    {
        if ($attachmentDTO->item) {
            return $attachmentDTO;
        }

        if ($attachmentDTO->attachedwith) {
            return $attachmentDTO->addData(
                item: class_basename_lower($attachmentDTO->attachedwith),
                itemId: $attachmentDTO->attachedwith->id,
            );
        }

        if (is_null($attachmentDTO->attachment)) {
            return $attachmentDTO;
        }

        return $attachmentDTO->addData(
            item: class_basename_lower($attachmentDTO->attachment->attachedwith),
            itemId: $attachmentDTO->attachment->attachedwith->id,
        );
    }

    private function broadcastAttachment($attachmentDTO)
    {
        $event = $this->getEvent($attachmentDTO);

        if (is_null($event)) {
            return;
        }

        broadcast($event)->toOthers();
    }

    private function getEvent($attachmentDTO)
    {
        if ($attachmentDTO->methodType === 'created') {
            return new NewAttachment($attachmentDTO);
        }

        if ($attachmentDTO->methodType === 'deleted') {
            return new DeleteAttachment($attachmentDTO);
        }

        return null;
    }

    private function checkAttachmentType(AttachmentDTO $attachmentDTO)
    {
        if (in_array($attachmentDTO->type, $this->attachmentTypes)) {
            return;
        }

        $this->throwAttachmentException(
            message: "sorry 😞, {$attachmentDTO->type} not a valid attachment type",
            data: $attachmentDTO
        );
    }

    private function throwAttachmentException($message, $data = null)
    {
        throw new AttachmentException(
            message: $message,
            data: $data
        );
    }

    private function makeAttachment(AttachmentDTO $attachmentDTO)
    {
        $attachment = PostAttachment::create([
            'note' => $attachmentDTO->note,
        ]);

        if (is_not_null($attachment)) {
            return $attachment;
        }

        $this->throwAttachmentException(
            message: 'attachment creation failed',
            data: $attachmentDTO
        );
    }

    public function createAttachmentWithSetData(AttachmentDTO $attachmentDTO)
    {
        $this->checkAttachmentType($attachmentDTO);

        $attachment = $this->makeAttachment($attachmentDTO);

        $attachmentDTO = $attachmentDTO->withAttachment($attachment);

        $attachment = $this->addAttachedby($attachmentDTO);

        $attachment = $this->addAttachable($attachmentDTO);

        $attachment = $this->addAttachedwith($attachmentDTO);

        $attachmentDTO = $this->increaseAccountPoints($attachmentDTO);

        return $attachment->refresh();
    }

    private function addAttachedby(AttachmentDTO $attachmentDTO)
    {
        $attachmentDTO->attachment->attachedby()->associate($attachmentDTO->addedby);
        $attachmentDTO->attachment->save();

        return $attachmentDTO->attachment->refresh();
    }

    private function addAttachedwith(AttachmentDTO $attachmentDTO)
    {
        if (is_null($attachmentDTO->attachedwith)) {
            return $attachmentDTO->attachment;
        }

        $attachmentDTO->attachment->attachedwith()->associate($attachmentDTO->attachedwith);
        $attachmentDTO->attachment->save();

        return $attachmentDTO->attachment->refresh();
    }

    private function addAttachable(AttachmentDTO $attachmentDTO)
    {
        if (is_null($attachmentDTO->attachable)) {
            return $attachmentDTO->attachment;
        }

        $attachmentDTO->attachment->attachable()->associate($attachmentDTO->attachable);
        $attachmentDTO->attachment->save();

        return $attachmentDTO->attachment->refresh();
    }

    private function increaseAccountPoints($attachmentDTO)
    {
        $this->increasePointsOfAccount($attachmentDTO->addedby);

        return $attachmentDTO;
    }
}
