<?php

declare(strict_types=1);

namespace Ikeraslt\CommissionCalculator\Contracts;

interface CurrencyConverter
{
    public function getCurrency(): string;

    public function setCurrency(string $currency): self;

    public function getDate(): string;

    public function setDate(string $date): self;

    public function convertToCurrency(float $amount): float;

    public function convertToBase(float $amount): float;
}
