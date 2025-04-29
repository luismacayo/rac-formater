<?php

namespace luismacayo\RacFormater\providers\weather\infrastructure;

use luismacayo\RacFormater\common\infrastructure\RequestInterface;

final class WeatherRequest implements RequestInterface
{

    public function __construct(
        public string $city,
        public string $apiKey,
    )
    {
    }

    public function getMethod(): string
    {
        return 'GET';
    }

    public function getUrl(): string
    {
        return "http://api.weatherapi.com/v1/current.json?key=$this->apiKey&q=$this->city";
    }

    public function getHeaders(): array
    {
        return [
            'Content-Type: application/json',
            'Accept: application/json',
        ];
    }

    public function getBody(): ?string
    {
        return null;
    }

    public function getOptions(): array
    {
        return [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_CONNECTTIMEOUT => 10,
        ];
    }
}