<?php

namespace App\Services;

use App\DTOs\SubjectDTO;
use App\Exceptions\AccountNotFoundException;
use App\Exceptions\SubjectException;
use App\Traits\AliasesServiceTrait;
use App\Traits\ServiceTrait;
use App\YourEdu\Subject;
use Illuminate\Support\Str;

class SubjectService
{
    use ServiceTrait,
        AliasesServiceTrait;

    public function createSubjectAsAttachment(SubjectDTO $subjectDTO)
    {
        $this->checkAccountType($subjectDTO);

        $this->ensureSubjectDoesntExist($subjectDTO);

        $subjectDTO = $this->setAddedby($subjectDTO);

        $subject = $this->makeSubject($subjectDTO);

        $this->createAliases($subjectDTO->withAliasable($subject));

        return $subject->refresh();
    }

    private function ensureSubjectDoesntExist($subjectDTO)
    {
        if (!Subject::query()->whereName($subjectDTO->name)->exists()) {
            return;
        }

        $this->throwSubjectException(
            message: "sorry 😞, there is already a subject with the name {$subjectDTO->name}",
            data: $subjectDTO
        );
    }

    private function makeSubject($subjectDTO)
    {
        return $subjectDTO->addedby->uniqueSubjectsAdded()->create([
            'name' => $subjectDTO->name,
            'description' => $subjectDTO->description,
            'rationale' => $subjectDTO->rationale,
        ]);
    }

    private function setAddedby($subjectDTO)
    {
        if ($subjectDTO->addedby) {
            return $subjectDTO;
        }

        return $subjectDTO->withAddedby(
            $this->getModel($subjectDTO->account, $subjectDTO->accountId)
        );
    }

    private function checkAccountType($subjectDTO)
    {
        if (!in_array($subjectDTO->account, ['learner', 'parent'])) {
            return;
        }

        $this->throwSubjectException(
            message: 'sorry 😞, learners or parents can only create an alias of a subject',
            data: $subjectDTO
        );
    }

    public function createSubjectAttachmentAlias(SubjectDTO $subjectDTO)
    {
        $mainSubject = $this->getModel('subject', $subjectDTO->subjectId);

        $subjectDTO = $this->setAddedby($subjectDTO);

        $alias = $this->createAlias(
            $subjectDTO
                ->withAliasable($subject)
                ->setAlias()
        );

        if (is_not_null($alias)) {
            return $subject->refresh();
        }

        $this->throwSubjectException(
            message: 'alias was not created',
            data: $subjectDTO
        );
    }

    public function deleteSubjectAsAttachment(SubjectDTO $subjectDTO)
    {
        $subject = $this->getModel('subject', $subjectDTO->subjectId);

        $this->checkAttachmentAuthorization($subject, $subjectDTO);

        $deletionStatus = $subject->delete();

        if ($deletionStatus) {
            return;
        }

        $this->throwSubjectException(
            message: "deletion of the subject, with name: {$subject->name}, failed",
            data: $subjectDTO
        );
    }

    public static function subjectAttachItem($subjectId, $item, $activity)
    {
        if (is_null(
            $item->subjects->where('id', $subjectId)->first()
        )) {
            $pivotArray = is_string($activity) ? ['activity' => Str::upper($activity)] : [];
            $item->subjects()->attach($subjectId, $pivotArray);
            $item->save();
        }
    }

    public static function subjectUnattachItem($subjectId, $item)
    {
        if (!is_null(
            $item->subjects->where('id', $subjectId)->first()
        )) {
            $item->subjects()->detach($subjectId);
            $item->save();
        }
    }

    public static function getSubjects()
    {
        return Subject::all();
    }

    private function throwSubjectException($message, $data = null)
    {
        throw new SubjectException(
            message: $message,
            data: $data
        );
    }
}
