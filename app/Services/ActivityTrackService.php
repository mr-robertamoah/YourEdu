<?php

namespace App\Services;

use App\DTOs\ActivityTrackDTO;
use App\YourEdu\ActivityTrack;

class ActivityTrackService
{
    public function trackActivity(ActivityTrackDTO $activityTrackDTO)
    {

        $activityTrack = ActivityTrack::create([
            'action' => $activityTrackDTO->action
        ]);

        $activityTrack->for()->associate($activityTrackDTO->activityfor);
        $activityTrack->what()->associate($activityTrackDTO->activity);
        $activityTrack->who()->associate($activityTrackDTO->performedby);
        $activityTrack->save();

        return $activityTrack;
    }
}