<?php

declare(strict_types=1);

namespace Ikeraslt\CommissionCalculator\Service;

use Ikeraslt\CommissionCalculator\Contracts\CurrencyConverter as CurrencyConverterInterface;
use Ikeraslt\CommissionCalculator\Exceptions\OperationNotSupported;
use Ikeraslt\CommissionCalculator\Operation\Operation;

class OperationResolver
{
    private const OPERATION_NAMESPACE = 'Ikeraslt\CommissionCalculator\Operation\\';
    private const USER_SPECIFIC_TYPES = [Operation::TYPE_WITHDRAW];
    private string $operationType;
    private string $userType;
    private CurrencyConverterInterface $converter;

    public function __construct(CurrencyConverterInterface $converter)
    {
        $this->converter = $converter;
    }

    public function getOperationType(): string
    {
        return $this->operationType;
    }

    public function setOperationType(string $operationType): self
    {
        $this->operationType = $operationType;

        return $this;
    }

    public function getUserType(): string
    {
        return $this->userType;
    }

    public function setUserType(string $userType): self
    {
        $this->userType = $userType;

        return $this;
    }

    public function resolve(): Operation
    {
        $className = $this->getClassName();

        if (!class_exists($className)) {
            throw new OperationNotSupported('Operation class not found');
        }

        $class = new $className($this->converter);

        if (!$class instanceof Operation) {
            throw new OperationNotSupported('Provided type is not an instance of Operation');
        }

        $class->setType($this->operationType);

        return $class;
    }

    private function getClassName(): string
    {
        $className = '';

        if ($this->isUserSpecific()) {
            $className .= ucfirst($this->userType);
        }

        $className .= ucfirst($this->operationType);

        return static::OPERATION_NAMESPACE.$className;
    }

    private function isUserSpecific(): bool
    {
        return in_array($this->operationType, static::USER_SPECIFIC_TYPES, true);
    }
}
