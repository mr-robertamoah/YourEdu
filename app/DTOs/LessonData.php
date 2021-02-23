<?php 

namespace App\DTOs;

use App\Contracts\ItemDataContract;
use Illuminate\Http\Request;

class LessonData implements ItemDataContract
{
    public string | null $lessonId;
    public string | null $title;
    public bool | null $intro;
    public bool | null $free;
    public bool $main;
    public array $links;
    public array $removedLinks;
    public array $removedFiles;
    public array $editedLinks;
    public array $items;
    public array $removedItems;
    public array $attachments;
    public array $removedAttachments;
    public array $files;
    public object | null $discussionData;
    public array $discussionFiles;
    public string | null $action;
    public string | null $owner;
    public string | null $ownerId;
    public string | null $adminId;
    public string | null $account;
    public string | null $accountId;
    public string | null $description;
    public string | null $type;
    public string | null $state;
    public array $paymentData;
    public array $removedPaymentData;
    public int | null $userId;

    public static function createFromRequest(Request $request, bool $main = false)
    {
        $self = new static();

        $self->lessonId = $request->lessonId;
        $self->adminId = $request->adminId;
        $self->action = $request->action;
        $self->title = $request->title;
        $self->owner = $request->owner;
        $self->ownerId = $request->ownerId;
        $self->account = $request->account;
        $self->accountId = $request->accountId;
        $self->userId = (int) $request->user()->id;
        $self->description = $request->description;
        $self->state = $request->state;
        $self->type = $request->type;
        $self->main = $main;
        $self->free = json_decode($request->free);
        $self->intro = json_decode($request->intro);
        $self->items = !is_null(json_decode($request->items)) ? 
            json_decode($request->items) : [];
        $self->removedItems = !is_null(json_decode($request->removedItems)) ? 
            json_decode($request->removedItems) : [];
        $self->removedFiles = !is_null(json_decode($request->removedFiles)) ? 
            json_decode($request->removedFiles) : [];
        $self->attachments = !is_null(json_decode($request->attachments)) ? 
            json_decode($request->attachments) : [];
        $self->removedAttachments = !is_null(json_decode($request->removedAttachments)) ? 
            json_decode($request->removedAttachments) : [];
        $self->removedPaymentData = !is_null(json_decode($request->removedPaymentData)) ? 
            json_decode($request->removedPaymentData) : [];
        $self->paymentData = !is_null(json_decode($request->paymentData)) ? 
            json_decode($request->paymentData) : [];
        $self->editedLinks = LinkData::createFromArray(json_decode($request->editedLinks));
        $self->links = LinkData::createFromArray(json_decode($request->links));
        $self->removedLinks = LinkData::createFromArray(json_decode($request->removedLinks));
        $self->discussionData = json_decode($request->discussionData);
        $self->discussionFiles = $request->hasFile('discussionFile') ? 
            $request->file('discussionFile') : [];
        $self->files = $request->hasFile('files') ? 
            $request->file('files') : [];

        return $self;
    }
}