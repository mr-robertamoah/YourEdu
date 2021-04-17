<?php

namespace App\Services;

use App\DTOs\SubscriptionDTO;
use App\Exceptions\SubscriptionException;
use App\Traits\ServiceTrait;
use Illuminate\Support\Str;

class SubscriptionService
{
    use ServiceTrait;

    public static function set(SubscriptionDTO $subscriptionDTO)
    {
        $subscription = $subscriptionDTO->dashboardItem->subscriptions()->create([
            'name' => $subscriptionDTO->name,
            'amount' => $subscriptionDTO->amount,
            'description' => $subscriptionDTO->description,
            'for' => Str::upper($subscriptionDTO->for),
            'period' => Str::upper($subscriptionDTO->period),
        ]);

        (new static)->checkSubscription($subscription, $subscriptionDTO);

        $subscription->ownedby()->associate($subscriptionDTO->addedby);
        $subscription->save();

        return $subscription;
    }

    private function checkSubscription($subscription, $subscriptionDTO)
    {
        if (!is_null($subscription)) {
            return;
        }

        $this->throwSubscriptionException(
            message: 'failed to create subscription',
            data: $subscriptionDTO
        );
    }

    private function throwSubscriptionException($message, $data = null)
    {
        throw new SubscriptionException(
            message: $message,
            data: $data
        );
    }

    public static function unset(SubscriptionDTO $subscriptionDTO)
    {
        $subscription = (new static)->getModel('subscription', $subscriptionDTO->subscriptionId);
        
        $subscription->subscribable()->dissociate($subscriptionDTO->dashboardItem);
        $subscription->delete();
    }
}