<?php

namespace Ikeraslt\CommissionCalculator\Tests\Mocks;

use Ikeraslt\CommissionCalculator\Service\CurrencyConverter as BaseConverter;

class CurrencyConverter extends BaseConverter
{
    protected function getRate(): float
    {
        $rates = [
            'USD' => 1.1497,
            'JPY' => 129.53,
        ];

        return $rates[$this->getCurrency()];
    }
}
