<?php 

namespace App\DTOs;

use App\Contracts\ItemDataContract;
use Illuminate\Http\Request;

class ExtracurriculumData implements ItemDataContract
{
    public string | null $name;
    public bool | null $facilitate;
    public array | null $classes;
    public array | null $removedClasses;
    public array | null $attachments;
    public array | null $removedAttachments;
    public string | null $extracurriculumId;
    public object | null $discussionData;
    public array | null $discussionFiles;
    public string | null $action;
    public string | null $owner;
    public string | null $ownerId;
    public string | null $adminId;
    public string | null $account;
    public string | null $accountId;
    public string | null $description;
    public string | null $type;
    public string | null $state;
    public array | null $paymentData;
    public array | null $removedPaymentData;
    public int | null $userId;

    public static function createFromRequest(Request $request)
    {
        $self = new static();

        $self->extracurriculumId = $request->extracurriculumId;
        $self->adminId = $request->adminId;
        $self->action = $request->action;
        $self->name = $request->name;
        $self->owner = $request->owner;
        $self->ownerId = $request->ownerId;
        $self->account = $request->account;
        $self->accountId = $request->accountId;
        $self->userId = (int) $request->user()->id;
        $self->description = $request->description;
        $self->state = $request->state;
        $self->type = $request->type;
        $self->facilitate = json_decode($request->facilitate);
        $self->classes = json_decode($request->classes);
        $self->removedClasses = json_decode($request->removedClasses);
        $self->attachments = json_decode($request->attachments);
        $self->removedAttachments = json_decode($request->removedAttachments);
        $self->removedPaymentData = json_decode($request->removedPaymentData);
        $self->paymentData = json_decode($request->paymentData);
        $self->discussionData = json_decode($request->discussionData);
        $self->discussionFiles = $request->file('discussionFile');

        return $self;
    }
}