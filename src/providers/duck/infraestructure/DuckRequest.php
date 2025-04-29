<?php

namespace luismacayo\RacFormater\providers\duck\infraestructure;

use luismacayo\RacFormater\common\infrastructure\RequestInterface;

final class DuckRequest implements RequestInterface
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
        return "https://xxxxxxxxxx/?q={$this->city}&format=json&no_redirect=1&no_html=1&skip_disambig=1";
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