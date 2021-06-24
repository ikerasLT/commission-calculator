<?php

declare(strict_types=1);

namespace Ikeraslt\CommissionCalculator\Contracts;

interface Output
{
    public function write(string $text): void;
}
