<?php

namespace App\Services;

class ActivityTrackService
{
    public function createActivityTrack($what,$for,$who,$action)
    {
        $activityTrack = $for->activityTrack()->create([
            'action' => $action
        ]);
        $activityTrack->what()->associate($what);
        $activityTrack->who()->associate($who);
        $activityTrack->save();

        return $activityTrack;
    }
}