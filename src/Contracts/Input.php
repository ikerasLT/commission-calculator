<?php

declare(strict_types=1);

namespace Ikeraslt\CommissionCalculator\Contracts;

use Ikeraslt\CommissionCalculator\Operation\Operation;

interface Input
{
    public function setSource($source): void;

    /**
     * @return iterable|Operation[]
     */
    public function read(): iterable;
}
