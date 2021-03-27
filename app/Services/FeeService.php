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

        $feeables = [];
        $sections = [];
        array_push($sections,...$data['sections']);
        array_push($sections,...$data['academicYears']);

        if (count($sections) < 1) {

            throw new FeeException(
                "academic year or academic year section is required for fees"
            );
        }

        foreach ($sections as $section) {
            $feeable = (new static)->getModel($section->type,$section->id);
            if (is_null($feeable)) {
                throw new AccountNotFoundException("academic year section with id {$section->id} not found.");
            }

            $feeables[] = $feeable;
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
        (new static)->getModel('fee', $feeId);
        
        $item->fees()->where('id',$feeId)->first()?->delete();
        // $fee->delete();
    }

    private function getModel($account, $accountId)
    {
        $mainAccount = getYourEduModel($account, $accountId);
        if (is_null($mainAccount)) {
            throw new AccountNotFoundException("$account not found with id {$accountId}");
        }

        return $mainAccount;
    }
}