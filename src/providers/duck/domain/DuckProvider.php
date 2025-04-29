<?php

namespace luismacayo\RacFormater\providers\duck\domain;

use luismacayo\RacFormater\common\domain\ProviderInterface;
use luismacayo\RacFormater\common\infrastructure\exceptions\ServiceUnavailableException;
use luismacayo\RacFormater\common\infrastructure\RequestInterface;
use luismacayo\RacFormater\providers\duck\infraestructure\DuckRequest;
use luismacayo\RacFormater\providers\ProviderEnum;

class DuckProvider implements ProviderInterface
{

    function configure(array $config): void
    {
        // TODO: Implement configure() method.
    }

    function getName(): string
    {
        return ProviderEnum::DUCK_API->name;
    }

    function isValidRequest(mixed $request): bool
    {
        return true;
    }

    /**
     * @throws ServiceUnavailableException
     */
    function isValidResponse(mixed $response): bool
    {
        if(isset($response['error'])) {
            throw new ServiceUnavailableException(
                'Duck provider is not available'
            );
        }
        return false;
    }

    function buildTemperatureRequest(mixed $request): RequestInterface
    {
        return new DuckRequest(
            city: $request['city'] ?? '',
        );
    }

    function transformResponse(mixed $response): mixed
    {
        throw new \Exception('Duck provider does not support this method');
    }
}