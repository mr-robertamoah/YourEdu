<?php

namespace App\Services;

use Illuminate\Support\Str;

class SubscriptionService
{
    public static function setSubscription($item,$subscriptionData,$ownedby)
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
}