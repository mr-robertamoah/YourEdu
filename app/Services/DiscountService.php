<?php

namespace App\Services;

use App\DTOs\DiscountDTO;
use App\Exceptions\AccountNotFoundException;
use App\Exceptions\DiscountException;
use App\Traits\ServiceTrait;
use App\YourEdu\Discount;
use Illuminate\Support\Str;

class DiscountService
{
    use ServiceTrait;

    public function createDiscount(DiscountDTO $discountDTO)
    {
        $discountDTO = $this->setAddedby($discountDTO);

        $discountDTO = $this->setDiscountable($discountDTO);

        $this->checkDiscountDTOData($discountDTO);

        $discount = $this->addDiscount($discountDTO);

        $this->checkDiscount($discount);

        $discountDTO = $discountDTO->withDiscount($discount);

        $discount = $this->associateDiscountToOwnedbyAndItem($discountDTO);

        return $discount;
    }

    private function checkDiscount($discount)
    {
        if (is_not_null($discount)) {
            return;
        }

        $this->throwDiscountException(
            message: "creation of the discount failed",
            data: $discountDTO
        );
    }

    public function associateDiscountToOwnedbyAndItem
    (
        DiscountDTO $discountDTO
    )
    {
        $discount = $this->associateDiscountToOwnedby($discountDTO);
        $discount = $this->associateDiscountToItem($discountDTO);

        return $discount;
    }

    private function associateDiscountToItem($discountDTO)
    {
        if (is_null($discountDTO->discountable)) {
            return $discountDTO->discount;
        }

        $discountable = $discountDTO->discount->discountables()->create();
        $discountable->discountable()->associate(
            $discountDTO->discountable
        );
        $discountable->save();

        return $discountDTO->discount->refresh();
    }

    private function associateDiscountToOwnedby($discountDTO)
    {
        if (is_null($discountDTO->ownedby)) {
            return $discountDTO->discount;
        }

        $discountDTO->discount->ownedby()->associate($discountDTO->ownedby);
        $discountDTO->discount->save();

        return $discountDTO->discount;
    }

    private function addDiscount(DiscountDTO $discountDTO)
    {
        $discount = $discountDTO->addedby->addedDiscounts()->create([
            'name' => $discountDTO->name,
            'state' => $discountDTO->state,
            'percentage' => $discountDTO->percentage,
            'discounted_price' => $discountDTO->discountedPrice,
            'expires_at' => $discountDTO->expiresAt?->toDateTimeString(),
            'uuid' => Str::uuid()
        ]);

        return $discount;
    }

    private function checkDiscountDTOData($discountDTO)
    {
        if (is_not_null($discountDTO->percentage)) {
            return;
        }
        
        if (is_not_null($discountDTO->discountedPrice)) {
            return;
        }

        $this->throwDiscountException(
            message: "the discount you are trying to create should have at least a percentage or discounted price.",
            data: $discountDTO
        );
    }

    private function throwDiscountException($message, $data = null)
    {
        throw new DiscountException(
            message: $message,
            data: $data
        );
    }

    private function setDiscountable(DiscountDTO $discountDTO)
    {
        if (! $discountDTO->requiresDiscountable) {
            return $discountDTO;
        }

        if ($discountDTO->discountable) {
            return $discountDTO;
        }

        return $discountDTO->withDiscountable(
            $this->getModel($discountDTO->item, $discountDTO->itemId)
        );
    }

    private function setAddedby(DiscountDTO $discountDTO)
    {
        if ($discountDTO->addedby) {
            return $discountDTO;
        }

        return $discountDTO->withAddedby(
            $this->getModel($discountDTO->account, $discountDTO->accountId)
        );
    }

    private function setOwnedby(DiscountDTO $discountDTO)
    {
        if ($discountDTO->ownedby) {
            return $discountDTO;
        }

        return $discountDTO->withOwnedby(
            $this->getModel($discountDTO->account, $discountDTO->accountId)
        );
    }
}
