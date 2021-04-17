<?php

namespace App\DTOs;

use App\Contracts\ItemDataContract;
use App\Traits\PaymentDTOTrait;
use App\YourEdu\Discount;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class RequestDTO
{
    use PaymentDTOTrait;

    public ?Model $requester = null;
    public ?Model $sender = null;
    public array $requests = [];
    public array $sendees = [];
    public array $receivers = [];
    public array $items = [];
    public array $files = [];
    public array $removedFiles = [];
    public ?Model $request = null;
    public ?DiscountDTO $discountDTO = null;
    public ?Discount $discount = null;
    public ?ResponseDTO $responseDTO = null;
    public ?string $state = null;
    public ?string $account = null;
    public ?string $accountId = null;
    public ?string $wardId = null;
    public ?string $requestId = null;
    public ?string $message = null;
    public ?string $userId = null;
    public ?AdmissionDTO $admissionDTO = null;
    public ?string $adminId = null;
    public ?string $action = null;
    public ?PaymentDTO $paymentDTO = null;
    public ?PaymentDTO $removedPaymentDTO = null;
    public ?string $method = null;

    public function __toString() {
        return serialize($this);
    }

    public static function createFromRequest(Request $request)
    {
        $self = new static;

        $self->account = $request->account;
        $self->accountId = $request->accountId;
        $self->admissionDTO = $request->admissionData ? 
            static::getAdmissionDTO(
                $request->admissionData
            ) : null;
        $self->userId = $request->user()?->id;
        $self->requestId = $request->requestId;
        $self->wardId = $request->wardId;
        $self->adminId = $request->adminId;
        $self->action = $request->action;
        $self->discountDTO = $request->discountData ? 
            static::getDiscountDTO(
                $request->discountData
            ) : null;
        $self->files = $request->has('files') ? 
            $request->file('files') : [];
        $self->items = $request->items ?
            ModelDTO::createFromArray(
                json_decode($request->items)
            ) : [];
        $self->receivers = $request->receivers ?
            AccountDTO::createFromArray(
                json_decode($request->receivers)
            ) : [];
        $self->removedPaymentDTO = static::createPaymentDTOForRemovedPayments(
            $request->removedPaymentData
        );
        $self->paymentDTO = static::createPaymentDTOForPayments(
            $request->paymentType,
            $request->paymentData
        );

        return $self;
    }

    public static function createFromData
    (
        $action = null,
    )
    {
        $self = new static;

        $self->action = $action;

        return $self;
    }

    private static function getDiscountDTO($encodedDiscountData)
    {
        if (is_null($encodedDiscountData)) {
            return null;
        }

        if (is_string($encodedDiscountData)) {
            $encodedDiscountData = json_decode($encodedDiscountData);
        }

        return DiscountDTO::createFromData(
            name: $encodedDiscountData->name,
            percentage: $encodedDiscountData->percentage ?? null,
            discountedPrice: $encodedDiscountData->discountedPrice ?? null,
            expiresAt: $encodedDiscountData->expiresAt ?? null,
            state: "ACCEPTED",
            requiresDiscountable: false,
        );
    }

    private static function getAdmissionDTO($encodedAdmissionData)
    {
        if (is_null($encodedAdmissionData)) {
            return null;
        }

        if (is_string($encodedAdmissionData)) {
            $encodedAdmissionData = json_decode($encodedAdmissionData);
        }

        return AdmissionDTO::createFromData(
            gradeId: $encodedAdmissionData->gradeId ?? null,
            type: $encodedAdmissionData->type ?? null,
            state: "pending",
        );
    }

    public function withRequester(Model $requester)
    {
        $clone = clone $this;

        $clone->requester = $requester;

        return $clone;
    }

    public function withRequest(Model $request)
    {
        $clone = clone $this;

        $clone->request = $request;

        return $clone;
    }

    public function withDiscount(Discount $discount)
    {
        $clone = clone $this;

        $clone->discount = $discount;

        return $clone;
    }

    public function withSender(Model $sender)
    {
        $clone = clone $this;

        $clone->sender = $sender;

        return $clone;
    }

    public function withResponseDTO(ResponseDTO $responseDTO)
    {
        $clone = clone $this;

        $clone->responseDTO = $responseDTO;

        return $clone;
    }

    public function withSendee(Model $sendee)
    {
        $clone = clone $this;

        array_push($clone->sendees, $sendee);

        return $clone;
    }

    public function cleanUp()
    {
        $clone = clone $this;

        $clone->requester = null;
        $clone->requests = [];
        $clone->sendees = [];
        $clone->items = [];
        $clone->removedFiles = [];
        $clone->paymentDTO = null;
        $clone->discount = null;
        $clone->admissionDTO = null;
        $clone->discountDTO = null;
        $clone->files = [];
        $clone->sender = null;
        $clone->request = null;

        return $clone;
    }
}
