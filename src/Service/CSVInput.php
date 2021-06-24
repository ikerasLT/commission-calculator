<?php

declare(strict_types=1);

namespace Ikeraslt\CommissionCalculator\Service;

use Ikeraslt\CommissionCalculator\Contracts\CurrencyConverter as CurrencyConverterInterface;
use Ikeraslt\CommissionCalculator\Contracts\Input;
use Ikeraslt\CommissionCalculator\Operation\Operation;
use Ikeraslt\CommissionCalculator\User\User;
use Ikeraslt\CommissionCalculator\User\UserStorage;

class CSVInput implements Input
{
    private string $source;
    private OperationResolver $resolver;
    private UserStorage $userStorage;

    public function __construct(CurrencyConverterInterface $converter)
    {
        $this->resolver = new OperationResolver($converter);
        $this->userStorage = new UserStorage();
    }

    public function setSource($source): void
    {
        $this->source = $source;
    }

    public function read(): iterable
    {
        $operations = [];

        $fh = fopen($this->source, 'r');

        while ($entry = fgetcsv($fh)) {
            $operations[] = $this->resolveOperation($entry);
        }

        fclose($fh);

        return $operations;
    }

    protected function resolveOperation(array $entry): Operation
    {
        [$date, $userId, $userType, $operationType, $amount, $currency] = $entry;

        $operation = $this->getOperation($operationType, $userType);
        $user = $this->getUser((int) $userId, $userType);

        $operation->setDate($date)
            ->setUser($user)
            ->setAmount($amount)
            ->setCurrency($currency);

        return $operation;
    }

    private function getOperation(string $operationType, string $userType): Operation
    {
        $operation = $this->resolver
            ->setOperationType($operationType)
            ->setUserType($userType)
            ->resolve();

        return $operation;
    }

    private function getUser(int $userId, string $type): User
    {
        return $this->userStorage->getUser($userId, $type);
    }
}
