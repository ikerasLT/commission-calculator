<?php

declare(strict_types=1);

namespace Ikeraslt\CommissionCalculator\Operation;

use Ikeraslt\CommissionCalculator\Contracts\Withdrawal;

class BusinessWithdraw extends Operation implements Withdrawal
{
    private const COMMISSION_RATE = 0.005;

    public function getCommissionedAmount(): float
    {
        return $this->amount;
    }

    public function getCommissionRate(): float
    {
        return static::COMMISSION_RATE;
    }
}
