<?php

namespace App\Services;

use App\DTOs\GradeDTO;
use App\Exceptions\AccountNotFoundException;
use App\Exceptions\GradeException;
use App\Traits\AliasesServiceTrait;
use App\Traits\ServiceTrait;
use App\YourEdu\Grade;

class GradeService
{
    use ServiceTrait,
        AliasesServiceTrait;

    public function createGradeAsAttachment(GradeDTO $gradeDTO)
    {
        $this->checkAccountType($gradeDTO);

        $this->ensureGradeDoesntExist($gradeDTO);

        $gradeDTO = $this->setAddedby($gradeDTO);

        $grade = $this->makeGrade($gradeDTO);

        $this->createAliases($gradeDTO->withAliasable($grade));

        return $grade->refresh();
    }

    private function makeGrade($gradeDTO)
    {
        return $gradeDTO->addedby->uniqueGradesAdded()->create([
            'name' => $gradeDTO->name,
            'description' => $gradeDTO->description,
            'level' => $gradeDTO->level,
            'age_group' => $gradeDTO->ageGroup,
        ]);
    }

    private function ensureGradeDoesntExist($gradeDTO)
    {
        if (!Grade::query()->whereName($gradeDTO->name)->exists()) {
            return;
        }

        $this->throwGradeException(
            message: "sorry 😞, there is already a grade with the name {$gradeDTO->name}",
            data: $gradeDTO
        );
    }

    private function setAddedby($gradeDTO)
    {
        if ($gradeDTO->addedby) {
            return $gradeDTO;
        }

        return $gradeDTO->withAddedby(
            $this->getModel($gradeDTO->account, $gradeDTO->accountId)
        );
    }

    private function checkAccountType($gradeDTO)
    {
        if (!in_array($gradeDTO->account, ['learner', 'parent'])) {
            return;
        }

        $this->throwGradeException(
            message: 'sorry 😞, learners or parents can only create an alias of a grade',
            data: $gradeDTO
        );
    }

    public function createGradeAttachmentAlias(GradeDTO $gradeDTO)
    {
        $grade = $this->getModel('grade', $gradeDTO->gradeId);

        $gradeDTO = $this->setAddedby($gradeDTO);

        $alias = $this->createAlias(
            $gradeDTO
                ->withAliasable($grade)
                ->setAlias()
        );

        if (is_not_null($alias)) {
            return $grade->refresh();
        }

        $this->throwGradeException(
            message: 'alias was not created',
            data: $gradeDTO
        );
    }

    public function deleteGradeAsAttachment(GradeDTO $gradeDTO)
    {
        $grade = $this->getModel('grade', $gradeDTO->gradeId);

        $this->checkAttachmentAuthorization($grade, $gradeDTO);

        $deletionStatus = $grade->delete();

        if ($deletionStatus) {
            return;
        }

        $this->throwGradeException(
            message: "deletion of the grade, with name: {$grade->name}, failed",
            data: $gradeDTO
        );
    }

    public static function gradeAttachItem($gradeId, $item)
    {
        if ($item->grades->where('id', $gradeId)->exists()) {
            return;
        }

        $item->grades()->attach($gradeId);
        $item->save();
    }

    public static function gradeUnattachItem($gradeId, $item)
    {
        if ($item->grades->where('id', $gradeId)->exists()) {
            $item->grades()->detach($gradeId);
            $item->save();
        }
    }

    private function throwGradeException($message, $data = null)
    {
        throw new GradeException(
            message: $message,
            data: $data
        );
    }
}
