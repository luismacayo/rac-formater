<?php

namespace luismacayo\RacFormater\providers\wttr\domain;

use luismacayo\RacFormater\common\domain\ProviderInterface;
use luismacayo\RacFormater\common\infrastructure\RequestInterface;
use luismacayo\RacFormater\matrix\domain\TemperatureDTO;
use luismacayo\RacFormater\providers\ProviderEnum;
use luismacayo\RacFormater\providers\wttr\infraestructure\WttrRequest;

class WttrProvider implements ProviderInterface
{

    function configure(array $config): void
    {
        // TODO: Implement configure() method.
    }

    function getName(): string
    {
        return ProviderEnum::WTTR_API->name;
    }

    function isValidRequest(mixed $request): bool
    {
        if (!isset($request['city']) || !is_string($request['city'])) {
            return false;
        }
        return true;
    }

    function isValidResponse(mixed $response): bool
    {
        $weatherData = json_decode($response, true);
        if (!isset($weatherData['current_condition'][0]['temp_C'])) {
            return false;
        }
        return true;
    }

    function buildTemperatureRequest(mixed $request): RequestInterface
    {
        return new WttrRequest(
            city: $request['city'],
        );
    }

    function transformResponse(mixed $response): mixed
    {
        $weatherData = json_decode($response, true);
        $temperature = $weatherData['current_condition'][0]['temp_C'];
        return new TemperatureDTO(
            city: '__',
            temperature: $temperature,
        );
    }
}