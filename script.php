<?php

use Ikeraslt\CommissionCalculator\Service\ConsoleOutput;
use Ikeraslt\CommissionCalculator\Service\CSVInput;
use Ikeraslt\CommissionCalculator\Service\CurrencyConverter;
use Ikeraslt\CommissionCalculator\Service\Processor;

require __DIR__ . '/vendor/autoload.php';

$output = new ConsoleOutput();

if (empty($argv[1])) {
    $output->write('Input file required');
    exit(1);
}

$source = realpath($argv[1]);

if (! $source || ! file_exists($source)) {
    $output->write('Input file not found');
    exit(1);
}

$converter = new CurrencyConverter();

$input = new CSVInput($converter);
$input->setSource($source);


$processor = new Processor($input, $output, $converter);
$processor->process();
