<?php 

namespace App\DTOs;

use App\Contracts\ItemDataContract;
use App\Contracts\PostTypeDTOContract;
use App\YourEdu\Lesson;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class LessonDTO extends PostTypeDTOContract
{
    public ?Carbon $publishedAt = null;
    public string | null $lessonId;
    public string | null $title;
    public string | null $ageGroup;
    public bool | null $intro;
    public bool | null $free;
    public bool $main = false;
    public array $links;
    public array $removedLinks;
    public array $removedFiles;
    public array $editedLinks;
    public array $items;
    public array $removedItems;
    public array $attachments;
    public array $removedAttachments;
    public array $files;
    public ?object $discussionData = null;
    public ?array $discussionFiles = [];
    public string $action = 'delete';
    public string | null $owner;
    public string | null $ownerId;
    public ?string $adminId = null;
    public string | null $account;
    public string | null $accountId;
    public string | null $description;
    public string | null $state;
    public ?PaymentDTO $paymentDTO = null;
    public ?PaymentDTO $removedPaymentDTO = null;
    public int | null $userId;
    public ?Lesson $lesson = null;
    public ?Model $addedby = null;
    public ?Model $ownedby = null;
    public ?Model $lessonable = null;
    public ?string $methodType = null;
    public ?string $method = null;

    public static function new()
    {
        return new static;
    }

    public static function createFromRequest
    (
        Request $request, bool $main = false
    )
    {
        $self = new static();

        $self->title = $request->title;
        $self->description = $request->description;
        $self->publishedAt = $request->published ?
            Carbon::parse($request->published) : null;
        $self->editedLinks = LinkDTO::createFromArray(
            $request->editedLinks ? json_decode($request->editedLinks) : []
        );
        $self->links = LinkDTO::createFromArray(
            $request->links ? json_decode($request->links) : []
        );
        $self->removedLinks = LinkDTO::createFromArray(
            $request->removedLinks ? json_decode($request->removedLinks) : []
        );
        $self->lessonId = $request->lessonId;
        $self->adminId = $request->adminId;
        $self->action = $request->action;
        $self->owner = $request->owner;
        $self->ownerId = $request->ownerId;
        $self->account = $request->account;
        $self->accountId = $request->accountId;
        $self->userId = (int) $request->user()->id;
        $self->state = $request->state;
        $self->main = $main;
        $self->free = $request->free ? json_decode($request->free) : null;
        $self->intro = $request->intro ? json_decode($request->intro) : null;
        $self->items = $request->items ? 
            json_decode($request->items) : [];
        $self->removedItems = $request->removedItems ? 
            json_decode($request->removedItems) : [];
        $self->attachments = $request->attachments ? 
            ModelDTO::createFromArray(
                json_decode($request->attachments)
            ) : [];
        $self->removedAttachments = $request->removedAttachments ? 
            ModelDTO::createFromArray(
                json_decode($request->removedAttachments)
            ) : [];
        $self->removedPaymentDTO = static::createPaymentDTOForRemovedPayments(
            $request->removedPaymentData
        );
        $self->paymentDTO = static::createPaymentDTOForPayments(
            $request->paymentType,
            $request->paymentData
        );
        $self->discussionData = $request->discussionData ?
            json_decode($request->discussionData) : null;
        $self->discussionFiles = $request->hasFile('discussionFile') ? 
            $request->file('discussionFile') : [];
        $self->removedFiles = $request->removedTypeFiles ?
            FileDTO::createFromArray(
                json_decode($request->removedTypeFiles)
            ) : [];
            
        if ($main) {
            $self->files = $request->hasFile('files') ? 
                $request->file('files') : [];
        } else {
            $self->files = $request->hasFile('typeFiles') ? 
                $request->file('typeFiles') : [];
        }

        return $self;
    }

    public function withLesson(Lesson $lesson)
    {
        $clone = clone $this;

        $clone->lesson = $lesson;

        return $clone;
    }

    public function withAddedby(Model $addedby)
    {
        $clone = clone $this;

        $clone->addedby = $addedby;

        return $clone;
    }

    public function resetFiles()
    {
        $clone = clone $this;

        $clone->files = [];

        return $clone;
    }
}