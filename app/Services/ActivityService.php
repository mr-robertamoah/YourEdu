<?php

namespace App\Services;

use App\DTOs\ActivityDTO;
use App\Exceptions\AccountNotFoundException;
use App\Exceptions\ActivityException;
use App\YourEdu\Activity;

class ActivityService
{
    public function createActivity(ActivityDTO $activityDTO)
    {
        $activity = $this->createOrUpdateActivity($activityDTO, 'create');

        $this->checkActivityFiles($activity, $activityDTO);

        $activityDTO = $activityDTO->withActivity($activity);

        $activity = $this->attachActivityToItem(
            activity: $activity,
            activityDTO: $activityDTO
        );

        $activity = $this->addActivityFiles($activity, $activityDTO);

        return $activity;
    }

    private function checkActivityFiles
    (
        Activity $activity = null,
        ActivityDTO $activityDTO
    )
    {
        $activityFilesDTO = FileService::countPossibleItemFiles(
            $activity,
            $activityDTO
        );

        if ($activityFilesDTO->imagesCount > 0) {
            return;
        }
        
        $this->throwActivityException(
            message: "an activity should have at least one file (image, video or audio)",
            data: $activityDTO
        );
    }

    private function createOrUpdateActivity
    (
        ActivityDTO $activityDTO,
        string $method
    ) : Activity
    {
        $data = [
            'description' => $activityDTO->description,
            'published_at' => $activityDTO->publishedAt?->toDateTimeString(),
        ];

        $activity = null;

        if ($method === 'create') {
            $activity = $activityDTO->addedby->activitiesAdded()
                ->create($data);
        }
        
        if ($method === 'update') {
            $activity = getYourEduModel('activity',$activityDTO->activityId);
                
            $activity?->update($data);
        }
        
        if (is_null($activity)) {
            $this->throwActivityException(
                message: "failed to {$method} activity.",
                data: $activityDTO
            );
        }

        return $activity->refresh();
    }

    private function addActivityFiles
    (
        Activity $activity,
        ActivityDTO $activityDTO,
    )
    {
        foreach ($activityDTO->files as $file) {

            FileService::createAndAttachFiles(
                account: $activityDTO->addedby, 
                file: $file,
                item: $activity
            );
        }

        return $activity->refresh();
    }

    private function attachActivityToItem
    (
        Activity $activity,
        ActivityDTO $activityDTO
    ) : Activity
    {
        if (!$activityDTO->activityfor) return $activity;

        $activity->activityfor()->associate($activityDTO->activityfor);
        $activity->save();

        return $activity->refresh();
    }

    private function throwActivityException
    (
        string $message,
        $data = null
    )
    {
        throw new ActivityException(
            message: $message,
            data: $data
        );
    }

    public function updateActivity(ActivityDTO $activityDTO)
    {
        $activity = $this->createOrUpdateActivity($activityDTO, 'update');

        $this->checkActivityFiles($activity, $activityDTO);

        $activityDTO = $activityDTO->withActivity($activity);

        $activity = $this->addActivityFiles($activity, $activityDTO);

        $activity = $this->removeActivityFiles($activity, $activityDTO);

        $this->checkActivityFiles($activity, $activityDTO);

        return $activity;
    }

    private function removeActivityFiles
    (
        Activity $activity,
        ActivityDTO $activityDTO
    )
    {
        foreach ($activityDTO->removedFiles as $file) {

            FileService::deleteAndUnattachFiles(
                file: $file,
                item: $activity
            );
        }

        return $activity->refresh();
    }

    public function deleteActivity(ActivityDTO $activityDTO)
    {
        $activity = $this->getActivityModel($activityDTO);

        $activity = $this->deleteActivityFiles($activity);

        return $activity->delete();
    }

    private function getActivityModel(ActivityDTO $activityDTO)
    {
        if ($activityDTO->activity) {
            return $activityDTO->activity;
        }

        $activity = getYourEduModel('activity', $activityDTO->activityId);
        if (is_null($activity)) {
            throw new AccountNotFoundException("activity with id {$activityDTO->activityId} not found.");
        }

        return $activity;
    }

    private function deleteActivityFiles
    (
        Activity $activity,
    ) : Activity
    {
        FileService::deleteYourEduItemFiles(
            item: $activity,
        );

        return $activity->refresh();
    }
}
