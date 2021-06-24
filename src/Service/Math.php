<?php

declare(strict_types=1);

namespace Ikeraslt\CommissionCalculator\Service;

class Math
{
    public static function roundUp(float $number, int $precision): float
    {
        $rounded = round($number, $precision);

        if ($number > $rounded) {
            $factor = 10 ** (-1 * $precision);

            $rounded += $factor;
        }

        return $rounded;
    }
}
