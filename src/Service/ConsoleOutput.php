<?php

declare(strict_types=1);

namespace Ikeraslt\CommissionCalculator\Service;

use Ikeraslt\CommissionCalculator\Contracts\Output;

class ConsoleOutput implements Output
{
    public function write(string $text): void
    {
        echo $text.PHP_EOL;
    }
}
