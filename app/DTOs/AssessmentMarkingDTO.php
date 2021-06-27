<?php

namespace App\DTOs;

use App\Traits\DTOTrait;
use App\YourEdu\Assessment;
use App\YourEdu\Work;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class AssessmentMarkingDTO
{
    use DTOTrait;

    public ?string $remarks = null;
    public ?string $assessmentId = null;
    public ?string $account = null;
    public ?string $accountId = null;
    public ?string $workId = null;
    public ?string $type = null;
    public ?Assessment $assessment = null;
    public ?MarkDTO $markDTO = null;
    public array $markDTOs = [];
    public ?Work $work = null;
    public ?Model $marker = null;

    public static function createFromRequest(Request $request)
    {
        $self = new static;

        $self->assessmentId = $request->assessmentId;
        $self->account = $request->account;
        $self->accountId = $request->accountId;
        $self->workId = $request->workId;
        $self->remarks = $request->remarks;
        $self->type = $request->type;
        $self->userId = $request->user()?->id;
        
        if ($self->type === 'all') {
            
            $self->markDTOs = self::getMarkDTOs($request);
        }
        
        if ($self->type === 'one') {
            
            $self->markDTO = self::getMarkDTO($request);
        }
        
        return $self;
    }

    private static function getMarkDTOs($request)
    {
        if (is_null($request->marks)) {
            return [];
        }
        
        $marks = $request->marks;

        if (is_string($marks)) {
            $marks = (array) json_decode($marks);
        }

        $dto = [];
        foreach ($marks as $mark) {
            $dto[] = self::getMarkDTOFromMark($mark);
        }

        return $dto;
    }

    private static function getMarkDTO($request)
    {
        if (is_null($request->mark)) {
            return null;
        }

        $mark = $request->mark;

        if (is_string($mark)) {
            $mark = json_decode($mark);
        }

        return self::getMarkDTOFromMark($mark);
    }

    private static function getMarkDTOFromMark($mark)
    {
        return MarkDTO::new()
            ->addData(
                item: 'answer',
                itemId: $mark->answerId ?? null,
                mark: $mark->mark ?? null,
                remarks: $mark->remarks ?? null,
            );
    }

    public function setUpMarker()
    {
        $clone = clone $this;

        if ($clone->type === 'one' && $clone->markDTO) {
            $clone->markDTO = $clone->markDTO->withMarkedby(
                $clone->marker
            );

            return $clone;
        }

        for ($i=0; $i < count($clone->markDTOs); $i++) {
            $clone->markDTOs[$i] = $clone->markDTOs[$i]
                ->withMarkedby($clone->marker);
        }

        return $clone;
    }

    public function hasMarkingData()
    {
        if ($this->type === 'one' && $this->markDTO) {
            return true;
        }
        
        if ($this->type === 'all' && $this->markDTOs && count($this->markDTOs)) {
            return true;
        }

        return false;
    }
}
