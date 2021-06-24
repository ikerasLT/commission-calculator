<?php

declare(strict_types=1);

namespace Ikeraslt\CommissionCalculator\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Ikeraslt\CommissionCalculator\Exceptions\ConversionUnsuccessful;

class CurrencyConverter implements \Ikeraslt\CommissionCalculator\Contracts\CurrencyConverter
{
    public const BASE_CURRENCY = 'EUR';
    public const DATE_FORMAT = 'Y-m-d';
    private const API_KEY = '6021416949a868b5119d80c37d0ad8c1';
    private Client $client;
    private string $currency;
    private string $date;

    public function __construct()
    {
        $this->client = new Client(['base_uri' => 'http://api.exchangeratesapi.io/v1/']);
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

    public function getDate(): string
    {
        return $this->date;
    }

    public function setDate(string $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function convertToCurrency(float $amount): float
    {
        if ($this->currency === static::BASE_CURRENCY) {
            return $amount;
        }

        $rate = $this->getRate();

        return $amount * $rate;
    }

    public function convertToBase(float $amount): float
    {
        if ($this->currency === static::BASE_CURRENCY) {
            return $amount;
        }

        $rate = $this->getRate();

        return $amount / $rate;
    }

    protected function getRate(): float
    {
        $request = $this->resolveRequest();

        try {
            $response = $this->client->get($this->date, ['query' => $request]);
        } catch (ClientException $e) {
            throw new ConversionUnsuccessful('Couldn\'t get the rate for conversion');
        }
        $json = $response->getBody()->getContents();

        $data = json_decode($json, true);

        if (!isset($data['rates']) || !isset($data['rates'][$this->currency])) {
            throw new ConversionUnsuccessful('Couldn\'t get the rate for conversion');
        }

        return $data['rates'][$this->currency];
    }

    private function resolveRequest(): array
    {
        return [
            'access_key' => static::API_KEY,
            'base' => static::BASE_CURRENCY,
            'symbols' => $this->currency,
        ];
    }
}
