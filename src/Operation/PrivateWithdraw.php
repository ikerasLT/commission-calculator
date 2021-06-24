<?php

declare(strict_types=1);

namespace Ikeraslt\CommissionCalculator\Operation;

use Ikeraslt\CommissionCalculator\Contracts\Withdrawal;

class PrivateWithdraw extends Operation implements Withdrawal
{
    private const COMMISSION_RATE = 0.003;
    private const FREE_WITHDRAWALS = 3;
    private const FREE_WITHDRAWAL_AMOUNT = 1000;

    public function getCommissionedAmount(): float
    {
        if ($this->withdrawalLimitReached()) {
            return $this->amount;
        }

        if (!$this->withdrawalAmountLimitReached()) {
            return 0;
        }

        $weekAmount = $this->getWeekAmount();

        $this->converter
            ->setCurrency($this->currency)
            ->setDate($this->date->format($this->converter::DATE_FORMAT));

        $currentAmountInEuros = $this->converter->convertToBase($this->amount);

        $commissionAmountInEuros = min($weekAmount - static::FREE_WITHDRAWAL_AMOUNT, $currentAmountInEuros);

        return $this->converter->convertToCurrency($commissionAmountInEuros);
    }

    public function getCommissionRate(): float
    {
        return static::COMMISSION_RATE;
    }

    private function withdrawalLimitReached(): bool
    {
        $weekWithdrawals = $this->user->getWithdrawalCountInWeek($this->date);

        return $weekWithdrawals > static::FREE_WITHDRAWALS;
    }

    private function withdrawalAmountLimitReached(): bool
    {
        return $this->getWeekAmount() > static::FREE_WITHDRAWAL_AMOUNT;
    }

    private function getWeekAmount(): float
    {
        return $this->user->getTotalWithdrawalAmountInWeek($this->date);
    }
}
