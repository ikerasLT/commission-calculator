<?php

declare(strict_types=1);

namespace Ikeraslt\CommissionCalculator\Operation;

class Deposit extends Operation
{
    private const COMMISSION_RATE = 0.0003;

    public function getCommissionedAmount(): float
    {
        return $this->amount;
    }

    public function getCommissionRate(): float
    {
        return static::COMMISSION_RATE;
    }
}
