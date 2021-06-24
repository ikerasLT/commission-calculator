<?php

declare(strict_types=1);

namespace Ikeraslt\CommissionCalculator\Operation;

use Carbon\CarbonImmutable;
use Ikeraslt\CommissionCalculator\Service\CurrencyConverter;
use Ikeraslt\CommissionCalculator\Service\Math;
use Ikeraslt\CommissionCalculator\User\User;

abstract class Operation
{
    public const TYPE_DEPOSIT = 'deposit';
    public const TYPE_WITHDRAW = 'withdraw';
    protected const DATE_FORMAT = 'Y-m-d';
    protected CarbonImmutable $date;
    protected User $user;
    protected string $type;
    protected float $amount;
    protected string $currency;
    protected int $precision;
    protected CurrencyConverter $converter;

    public function __construct(CurrencyConverter $converter)
    {
        $this->converter = $converter;
    }

    abstract public function getCommissionedAmount(): float;

    abstract public function getCommissionRate(): float;

    public function getDate(): CarbonImmutable
    {
        return $this->date;
    }

    public function setDate(string $date): self
    {
        $this->date = CarbonImmutable::createFromFormat(static::DATE_FORMAT, $date);

        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function setAmount(string $amount): self
    {
        $this->amount = (float) $amount;
        $this->resolvePrecision($amount);

        return $this;
    }

    public function getPrecision(): int
    {
        return $this->precision;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    public function calculateCommission(): float
    {
        $amount = $this->getCommissionedAmount();
        $commission = $amount * $this->getCommissionRate();

        return Math::roundUp($commission, $this->precision);
    }

    protected function resolvePrecision(string $amount): void
    {
        $parts = explode('.', $amount);

        if (empty($parts[1])) {
            $this->precision = 0;
        } else {
            $this->precision = strlen($parts[1]);
        }
    }
}
