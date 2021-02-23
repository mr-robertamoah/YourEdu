<?php

namespace App\Services;

use App\Contracts\PaymentTypeContract;
use App\Exceptions\AccountNotFoundException;
use App\Exceptions\FeeException;

class FeeService extends PaymentTypeContract
{
    public static function set($item,$data,$addedby)
    {
        $fee = $item->fees()->create([
            'amount' => $data['amount']
        ]);
        $fee->addedby()->associate($addedby);
        // $fee->save();

        $feeables = [];
        $sections = [];
        array_push($sections,...$data['sections']);
        array_push($sections,...$data['academicYears']);
        if (count($sections)) {
            foreach ($sections as $section) {
                $feeable = getYourEduModel($section->type,$section->id);
                if (is_null($feeable)) {
                    throw new AccountNotFoundException("academic year section with id {$section->id} not found.");
                } else {
                    $feeables[] = $feeable;
                }
            }
        } else {
            throw new FeeException("academic year or academic year section is required for fees");
        }
        // $feeable = getYourEduModel($data['feeableData']['feeable'],$data['feeableData']['feeableId']);
        // if (is_null($feeable)) {
        //     throw new AccountNotFoundException("{$data['feeableData']['feeable']} was not found with id {$feeableData['feeableId']}");
        // }
        foreach ($feeables as $f) {
            $fee->feeable()->associate($f);
        }
        $fee->save();
    }

    public static function unset($item,$feeId)
    {
        $fee = getYourEduModel('fee', $feeId);
        if (is_null($fee)) {
            throw new AccountNotFoundException("fee not found with id {$feeId}");
        }

        $item->fees()->where('id',$feeId)->first()?->delete();
        // $fee->delete();
    }
}