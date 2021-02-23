<?php

namespace App\Services;

use App\Contracts\PaymentTypeContract;
use App\Exceptions\AccountNotFoundException;
use Illuminate\Support\Str;

class SubscriptionService extends PaymentTypeContract
{
    public static function set($item,$subscriptionData,$ownedby)
    {
        //the owned by is actually added by
        $subscription = $item->subscriptions()->create([
            'name' => $subscriptionData->name,
            'amount' => $subscriptionData->amount,
            'description' => $subscriptionData->description,
            'for' => Str::upper($subscriptionData->for)
        ]);
        $subscription->ownedby()->associate($ownedby);
        $subscription->save();
    }

    public static function unset($item,$subscriptionId)
    {
        $subscription = getYourEduModel('subscription', $subscriptionId);
        if (is_null($subscription)) {
            throw new AccountNotFoundException("subscription not found with id {$subscriptionId}");
        }

        $subscription->subscribable()->dissociate($item);
        $subscription->delete();
    }
}