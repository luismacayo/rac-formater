<?php

namespace luismacayo\RacFormater\providers\weather\domain;

use luismacayo\RacFormater\common\domain\ProviderInterface;
use luismacayo\RacFormater\common\infrastructure\RequestInterface;
use luismacayo\RacFormater\matrix\domain\TemperatureDTO;
use luismacayo\RacFormater\providers\ProviderEnum;
use luismacayo\RacFormater\providers\weather\infrastructure\WeatherRequest;

class WeatherProvider implements ProviderInterface
{
    private string $apiKey;

    public function getName(): string
    {
        return ProviderEnum::WEATHER_API->name;
    }

    public function isValidRequest(mixed $request): bool
    {
        if (!isset($request['city']) || !is_string($request['city'])) {
            return false;
        }
        return true;
    }

    public function isValidResponse(mixed $response): bool
    {
        $weatherData = json_decode($response, true);
        if (!isset($weatherData['current']['temp_c'])) {
            return false;
        }
        return true;
    }

    public function buildTemperatureRequest(mixed $request): RequestInterface
    {
        return new WeatherRequest(
            city: $request['city'],
            apiKey: $this->apiKey,
        );
    }

    public function transformResponse(mixed $response): TemperatureDTO
    {
        $weatherData = json_decode($response, true);
        $temperature = $weatherData['current']['temp_c'];
        return new TemperatureDTO(
            city: '__',
            temperature: $temperature,
        );
    }

    function configure(array $config): void
    {
        if (!isset($config['apiKey'])) {
            throw new \InvalidArgumentException('API key is required');
        }
        $this->apiKey = $config['apiKey'];
    }
}

{

}