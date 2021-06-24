<?php

declare(strict_types=1);

namespace Ikeraslt\CommissionCalculator\User;

use Carbon\CarbonImmutable;

class User
{
    public const TYPE_BUSINESS = 'business';
    public const TYPE_PRIVATE = 'private';
    private int $id;
    private string $type;
    private array $withdrawalAmountByWeek = [];
    private array $withdrawalsByWeek = [];

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

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

    public function addWithdrawal(CarbonImmutable $date, float $amount): void
    {
        $currentAmount = $this->getTotalWithdrawalAmountInWeek($date);
        $withdrawalKey = $this->getWithdrawalKey($date);

        $this->withdrawalAmountByWeek[$withdrawalKey] = $currentAmount + $amount;
        $this->withdrawalsByWeek[$withdrawalKey] = ($this->withdrawalsByWeek[$withdrawalKey] ?? 0) + 1;
    }

    public function getWithdrawalCountInWeek(CarbonImmutable $date): int
    {
        $withdrawalKey = $this->getWithdrawalKey($date);

        return $this->withdrawalsByWeek[$withdrawalKey] ?? 0;
    }

    public function getTotalWithdrawalAmountInWeek(CarbonImmutable $date): float
    {
        return $this->withdrawalAmountByWeek[$this->getWithdrawalKey($date)] ?? 0;
    }

    private function getWithdrawalKey(CarbonImmutable $date): string
    {
        return $date->startOfWeek(CarbonImmutable::MONDAY)->format('Y-W');
    }
}
