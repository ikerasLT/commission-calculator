<?php

namespace Ikeraslt\CommissionCalculator\Tests\Service;

use Ikeraslt\CommissionCalculator\Contracts\CurrencyConverter as CurrencyConverterInterface;
use Ikeraslt\CommissionCalculator\Contracts\Input;
use Ikeraslt\CommissionCalculator\Service\ConsoleOutput;
use Ikeraslt\CommissionCalculator\Tests\Mocks\CurrencyConverter;
use Ikeraslt\CommissionCalculator\Service\Processor;
use Ikeraslt\CommissionCalculator\Tests\Mocks\TestInput;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ProcessorTest extends TestCase
{
    /**
     * @dataProvider dataProviderForProcessTesting
     */
    public function testProcess(array $entries, array $expectations)
    {
        $converter = $this->getConverter();
        $input = $this->getInput($converter, $entries);
        $output = $this->getOutput();
        $output->expects($this->exactly(count($expectations)))->method('write')->withConsecutive(...$expectations);

        $processor = new Processor($input, $output, $converter);
        $processor->process();

    }

    public function dataProviderForProcessTesting(): array
    {
        $entries = [
            ['2014-12-31', '4', 'private', 'withdraw', '1200.00', 'EUR'],
            ['2015-01-01', '4', 'private', 'withdraw', '1000.00', 'EUR'],
            ['2016-01-05', '4', 'private', 'withdraw', '1000.00', 'EUR'],
            ['2016-01-05', '1', 'private', 'deposit', '200.00', 'EUR'],
            ['2016-01-06', '2', 'business', 'withdraw', '300.00', 'EUR'],
            ['2016-01-06', '1', 'private', 'withdraw', '30000', 'JPY'],
            ['2016-01-07', '1', 'private', 'withdraw', '1000.00', 'EUR'],
            ['2016-01-07', '1', 'private', 'withdraw', '100.00', 'USD'],
            ['2016-01-10', '1', 'private', 'withdraw', '100.00', 'EUR'],
            ['2016-01-10', '2', 'business', 'deposit', '10000.00', 'EUR'],
            ['2016-01-10', '3', 'private', 'withdraw', '1000.00', 'EUR'],
            ['2016-02-15', '1', 'private', 'withdraw', '300.00', 'EUR'],
            ['2016-02-19', '5', 'private', 'withdraw', '3000000', 'JPY'],
        ];

        $expectations = [
            ['0.60'],
            ['3.00'],
            ['0.00'],
            ['0.06'],
            ['1.50'],
            ['0'],
            ['0.70'],
            ['0.30'],
            ['0.30'],
            ['3.00'],
            ['0.00'],
            ['0.00'],
            ['8612'],
        ];

        return [[$entries, $expectations]];
    }

    private function getInput(CurrencyConverterInterface $converter, array $entries): Input
    {
        $input = new TestInput($converter);
        $input->setSource($entries);

        return $input;
    }

    private function getOutput(): MockObject
    {
        return $this->createMock(ConsoleOutput::class);
    }

    private function getConverter(): CurrencyConverterInterface
    {
        return new CurrencyConverter();
    }
}
