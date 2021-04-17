<?php

namespace App\Traits;

use App\DTOs\CommissionDTO;
use App\DTOs\FeeDTO;
use App\DTOs\PaymentDTO;
use App\DTOs\PriceDTO;
use App\DTOs\SalaryDTO;
use App\DTOs\SubscriptionDTO;

trait PaymentDTOTrait
{
    protected static function getMultiplePaymentTypeDTOBasedOnPaymentType
    (
        $paymentType,
        $data,
    )
    {
        $paymentData = [];

        if ($paymentType === 'fee') {
            foreach ($data as $fee) {
                $paymentData[] = FeeDTO::createFromData(
                    amount: $fee->amount,
                    feeables: $fee->feeables ?? [],
                    classId: $fee->classId ?? null,
                    account: $fee->account ?? null,
                    accountId: $fee->accountId ?? null,
                );
            }
        }

        if ($paymentType === 'price') {
            foreach ($data as $price) {
                $paymentData[] = PriceDTO::createFromData(
                    amount: $price->amount,
                    description: $price->description ?? null,
                    for: $price->for ?? "all",
                );
            }
        }

        if ($paymentType === 'subscription') {
            foreach ($data as $subscription) {
                $paymentData[] = SubscriptionDTO::createFromData(
                    amount: $subscription->amount,
                    description: $subscription->description ?? null,
                    for: $subscription->for ?? 'all',
                    period: $subscription->period ?? "year",
                    name: $subscription->name,
                );
            }
        }

        if ($paymentType === 'salary') {
            foreach ($data as $salary) {
                $paymentData[] = SalaryDTO::createFromData(
                    amount: $salary->amount,
                    currency: $salary->currency ?? null,
                    period: $salary->period ?? "month",
                    name: $salary->name ?? 'salary',
                );
            }
        }

        if ($paymentType === 'commission') {
            foreach ($data as $commission) {
                $paymentData[] = CommissionDTO::createFromData(
                    percentageOwned: $commission->percentageOwned ?? 0.1,
                );
            }
        }

        return $paymentData;
    }

    protected static function createPaymentDTOForPayments
    (
        $paymentType,
        $paymentData,
    )
    {
        if (is_null($paymentData)) {
            return null;
        }

        if (is_string($paymentData)) {
            $paymentData = json_decode($paymentData);
        }

        if (!is_array($paymentData) || !count($paymentData)) {
            return null;
        }

        return PaymentDTO::createFromData(
            type: $paymentType,
            multipleTypeDTO: static::getMultiplePaymentTypeDTOBasedOnPaymentType(
                $paymentType, $paymentData,
            )
        );
    }

    protected static function createPaymentDTOForRemovedPayments
    (
        $removedPaymentData
    )
    {
        if (is_null($removedPaymentData)) {
            return null;
        }

        if (is_string($removedPaymentData)) {
            $removedPaymentData = json_decode($removedPaymentData);
        }

        if (!is_array($removedPaymentData) || !count($removedPaymentData)) {
            return null;
        }

        return PaymentDTO::createFromData(
            multipleTypeDTO: static::getMultiplePaymentTypeDTOBasedOnIndividualTypes(
                $removedPaymentData
            )
        );
    }

    protected static function getMultiplePaymentTypeDTOBasedOnIndividualTypes
    (
        $removedPaymentData
    )
    {
        $dtos = [];

        foreach ($removedPaymentData as $data) {
            if ($data->type === 'fee') {
                $dtos[] = FeeDTO::createFromData(feeId: $data->id);
                continue;
            }

            if ($data->type === 'subscription') {
                $dtos[] = SubscriptionDTO::createFromData(subscriptionId: $data->id);
                continue;
            }

            if ($data->type === 'salary') {
                $dtos[] = SalaryDTO::createFromData(salaryId: $data->id);
                continue;
            }

            if ($data->type === 'commission') {
                $dtos[] = CommissionDTO::createFromData(commissionId: $data->id);
                continue;
            }

            $dtos[] = PriceDTO::createFromData(priceId: $data->id);
        }

        return $dtos;
    }
}
