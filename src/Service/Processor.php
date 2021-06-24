<?php

declare(strict_types=1);

namespace Ikeraslt\CommissionCalculator\Service;

use Ikeraslt\CommissionCalculator\Contracts\CurrencyConverter;
use Ikeraslt\CommissionCalculator\Contracts\Input;
use Ikeraslt\CommissionCalculator\Contracts\Output;
use Ikeraslt\CommissionCalculator\Contracts\Withdrawal;

class Processor
{
    private Input $input;
    private Output $output;
    private CurrencyConverter $converter;

    public function __construct(Input $input, Output $output, CurrencyConverter $converter)
    {
        $this->input = $input;
        $this->output = $output;
        $this->converter = $converter;
    }

    public function process()
    {
        foreach ($this->input->read() as $operation) {
            $date = $operation->getDate();
            $amount = $this->converter
                ->setDate($date->format($this->converter::DATE_FORMAT))
                ->setCurrency($operation->getCurrency())
                ->convertToBase($operation->getAmount());

            if ($operation instanceof Withdrawal) {
                $operation->getUser()->addWithdrawal($date, $amount);
            }

            $commission = $operation->calculateCommission();
            $this->output->write(number_format($commission, $operation->getPrecision(), '.', ''));
        }
    }
}
