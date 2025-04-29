<?php

namespace luismacayo\RacFormater\providers\decorated\domain;

use luismacayo\RacFormater\common\domain\ProviderInterface;
use luismacayo\RacFormater\common\infrastructure\RequestInterface;
use luismacayo\RacFormater\matrix\domain\TemperatureDTO;
use luismacayo\RacFormater\providers\ProviderEnum;
use luismacayo\RacFormater\providers\weather\domain\WeatherProvider;

class WeatherDecoratedProvider implements ProviderInterface
{

    private ProviderInterface $target;

    public function getName(): string
    {
        return ProviderEnum::WEATHER_DECORATED_API->name;
    }

    public function isValidRequest(mixed $request): bool
    {
        return $this->target->isValidRequest($request);
    }

    public function isValidResponse(mixed $response): bool
    {
        return $this->target->isValidResponse($response);
    }

    public function buildTemperatureRequest(mixed $request): RequestInterface
    {
        return $this->target->buildTemperatureRequest(
            $request
        );
    }

    public function transformResponse(mixed $response): TemperatureDTO
    {
        $weatherData = json_decode($response, true);
        $temperature = (float)$weatherData['current']['temp_c'];
        $temperature = $temperature * 9 / 5 + 32; // Convert to Fahrenheit
        return new TemperatureDTO(
            city: '__',
            temperature: $temperature,
        );
    }

    function configure(array $config): void
    {
        $this->target = new WeatherProvider();
        $this->target->configure($config);
    }
}

{

}