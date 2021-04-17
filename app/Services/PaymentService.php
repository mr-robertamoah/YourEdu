<?php

namespace App\Services;

use App\DTOs\FeeDTO;
use App\DTOs\PaymentDTO;

class PaymentService
{
    public static function setMultiplePaymentOnItemBasedOnPaymentType
    (
        PaymentDTO $paymentDTO
    )
    {
        if ($paymentDTO->doesntHaveRequiredData()) {
            return $paymentDTO;
        }
        
        if ($paymentDTO->type === 'price') {
            return (new PaymentService)->setMultiplePrices($paymentDTO);
        }
        
        if ($paymentDTO->type === 'fee') {
            return (new PaymentService)->setMultipleFees($paymentDTO);
        } 
        
        if ($paymentDTO->type === 'subscription') {
            return (new PaymentService)->setMultipleSubscriptions($paymentDTO);
        }
        
        if ($paymentDTO->type === 'salary') {
            return (new PaymentService)->setMultipleSalaries($paymentDTO);
        }
        
        if ($paymentDTO->type === 'commission') {
            return (new PaymentService)->setMultipleCommissions($paymentDTO);
        }

        return $paymentDTO;
    }

    private function setMultipleSalaries($paymentDTO)
    {
        $payments = [];

        foreach ($paymentDTO->multipleTypeDTO as $salaryDTO) {
            $salaryDTO = $salaryDTO->withAddedby($paymentDTO->addedby);

            if ($paymentDTO->ownedby) {
                $salaryDTO = $salaryDTO->withOwnedby($paymentDTO->ownedby);
            }
            
            $payments[] = SalaryService::set(
                $salaryDTO
            );
        }
        
        $paymentDTO->payments = $payments;
        return $paymentDTO;
    }

    private function setMultipleCommissions($paymentDTO)
    {
        $payments = [];

        foreach ($paymentDTO->multipleTypeDTO as $commissionDTO) {
            $commissionDTO = $commissionDTO
                ->withAddedby($paymentDTO->addedby);

            if ($paymentDTO->ownedby) {
                $commissionDTO = $commissionDTO->withOwnedby($paymentDTO->ownedby);
            }

            $payments[] = (new CommissionService)->createCommission(
                $commissionDTO
            );
        }
        
        $paymentDTO->payments = $payments;
        return $paymentDTO;
    }

    private function setMultipleFees(PaymentDTO $paymentDTO)
    {
        foreach ($paymentDTO->multipleTypeDTO as $feeDTO) {
            $feeDTO = ! $paymentDTO->dashboardItemCheck ? 
                $feeDTO->setToNotAttachFeeables() : $feeDTO;

            if ($paymentDTO->dashboardItem) {
                $feeDTO = $feeDTO
                    ->withDashboardItem($paymentDTO->dashboardItem);
            }

            $paymentDTO->payments[] = (new FeeService)->set(
                $feeDTO
                    ->withAddedby($paymentDTO->addedby)
            );
        }

        return $paymentDTO;
    }

    private function setMultipleSubscriptions($paymentDTO)
    {
        foreach ($paymentDTO->multipleTypeDTO as $subscriptionDTO) {
            $subscriptionDTO = $subscriptionDTO
                    ->withAddedby($paymentDTO->addedby);
            if ($paymentDTO->dashboardItem) {
                $subscriptionDTO = $subscriptionDTO
                        ->withDashboardItem($paymentDTO->dashboardItem);
            }
            $paymentDTO->payments[] = SubscriptionService::set(
                $subscriptionDTO
            );
        }

        return $paymentDTO;
    }

    private function setMultiplePrices($paymentDTO)
    {
        foreach ($paymentDTO->multipleTypeDTO as $priceDTO) {
            $priceDTO = $priceDTO
                    ->withAddedby($paymentDTO->addedby);
            if ($paymentDTO->dashboardItem) {
                $priceDTO = $priceDTO
                        ->withDashboardItem($paymentDTO->dashboardItem);
            }
            $paymentDTO->payments[] = (new PriceService)->set(
                $priceDTO
            );
        }

        return $paymentDTO;
    }

    public static function unsetMultiplePaymentFromItemBasedOnIndividualTypes
    (
        PaymentDTO $paymentDTO
    )
    {
        if (is_null($paymentDTO)) {
            return;
        }

        if (!is_array($paymentDTO->multipleTypeDTO)) {
            return;
        }

        foreach ($paymentDTO->multipleTypeDTO as $typeDTO) {
            $typeDTO = $typeDTO->withDashboardItem($paymentDTO->dashboardItem);

            static::unsetPaymentFromItemBasedOnIndividualType($typeDTO);
        }
    }

    public static function unsetPaymentFromItemBasedOnIndividualType($typeDTO)
    {
        if ($typeDTO->type === 'price') {
            PriceService::unset($typeDTO);
            return;
        }
            
        if ($typeDTO->type === 'subscription') {
            SubscriptionService::unset($typeDTO);
            return;
        }
            
        if ($typeDTO->type === 'salary') {
            salaryService::unset($typeDTO);
            return;
        }

        FeeService::unset($typeDTO);
    }
}

