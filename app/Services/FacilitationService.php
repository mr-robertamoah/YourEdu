<?php

namespace App\Services;

class FacilitationService
{
    public static function addFacilitationDetailsWithModels
    (
        $itemable, 
        $facilitatable, 
        $accountable
    )
    {
        $facilitationDetail = $itemable->facilitationDetails()->create();
        $facilitationDetail->facilitatable()->associate($facilitatable);
        $facilitationDetail->accountable()->associate($accountable);
        $facilitationDetail->save();
    }

    public static function removeFacilitationDetailsWithModels
    (
        $itemable,
        $facilitatable,
        $accountable
    )
    {
        $itemable->specificFacilitationDetail(
            $facilitatable, $accountable
        )?->delete();
    }
}
