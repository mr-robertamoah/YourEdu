<?php

namespace App\DTOs;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class SearchDTO
{
    public ?string $userId = null;
    public ?string $item = null;
    public ?string $itemId = null;
    public ?Model $itemModel = null;
    public ?string $search = null;
    public ?string $searchType = null;
    public array $excludedUserIds = [];
    public array $flaggedbyUserIds = [];

    public static function createFromRequest(Request $request)
    {
        $self = new static;

        $self->userId = $request->user()?->id;

        if ($request->discussionId) {
            $self->itemId = $request->discussionId;
            $self->item = 'discussion';
        }
        
        if ($request->assessmentId) {
            $self->itemId = $request->assessmentId;
            $self->item = 'assessment';
        }

        $self->search = $request->search;
        $self->searchType = $request->searchType;

        return $self;
    }

    public function withItemModel($itemModel)
    {
        $clone = clone $this;

        $clone->itemModel = $itemModel;

        return $clone;
    }

    public function addToExcludedUserIds(array $userIds)
    {
        $this->excludedUserIds = array_push($this->excludedUserIds, ...$userIds);

        return $this;
    }

    public function addToFlaggedbyUserIds(array $userIds)
    {
        $this->flaggedbyUserIds = array_push($this->flaggedbyUserIds, ...$userIds);

        return $this;
    }
}
