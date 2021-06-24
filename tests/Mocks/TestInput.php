<?php

namespace Ikeraslt\CommissionCalculator\Tests\Mocks;

use Ikeraslt\CommissionCalculator\Service\CSVInput;

class TestInput extends CSVInput
{
    private array $source = [];

    public function setSource($source): void
    {
        $this->source = $source;
    }

    public function read(): iterable
    {
        $operations = [];

        foreach ($this->source as $entry) {
            $operations[] = $this->resolveOperation($entry);
        }

        return $operations;
    }
}
