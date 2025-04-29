<?php

namespace luismacayo\RacFormater\providers\wttr\infraestructure;

use luismacayo\RacFormater\common\infrastructure\RequestInterface;

final class WttrRequest implements RequestInterface
{

    public function __construct(
        public string $city,
    )
    {
    }

    public function getMethod(): string
    {
        return 'GET';
    }

    public function getUrl(): string
    {
        return "https://wttr.in/" . urlencode($this->city) . "?format=j1";
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