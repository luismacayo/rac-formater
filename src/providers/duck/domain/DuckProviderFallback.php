<?php

namespace luismacayo\RacFormater\providers\duck\domain;

use luismacayo\RacFormater\common\domain\ProviderInterface;
use luismacayo\RacFormater\common\infrastructure\RequestInterface;
use luismacayo\RacFormater\providers\ProviderEnum;
use luismacayo\RacFormater\providers\wttr\domain\WttrProvider;

class DuckProviderFallback implements ProviderInterface
{
    private ProviderInterface $primaryProvider;
    private ProviderInterface $fallbackProvider;

    function configure(array $config): void
    {
        $this->primaryProvider = new DuckProvider();
        $this->primaryProvider->configure($config);

        $this->fallbackProvider = new WttrProvider();
        $this->fallbackProvider->configure($config);
    }

    function getName(): string
    {
        return ProviderEnum::DUCK_FALLBACK_API->name;
    }

    function isValidRequest(mixed $request): bool
    {
        return $this->primaryProvider->isValidRequest($request) && $this->fallbackProvider->isValidRequest($request);
    }

    function isValidResponse(mixed $response): bool
    {
        return $this->primaryProvider->isValidResponse($response)
            || $this->fallbackProvider->isValidResponse($response);
    }

    function buildTemperatureRequest(mixed $request): RequestInterface
    {
        try {
            return $this->primaryProvider->buildTemperatureRequest($request);
        } catch (\Exception $e) {
            codecept_debug('Primary provider failed: ' . $e->getMessage());
            return $this->fallbackProvider->buildTemperatureRequest($request);
        }
    }

    function transformResponse(mixed $response): mixed
    {
        try {
            if ($this->primaryProvider->isValidResponse($response)) {
                return $this->primaryProvider->transformResponse($response);
            }
        } catch (\Exception $e) {
            codecept_debug('Primary provider failed: ' . $e->getMessage());
        }
        return $this->fallbackProvider->transformResponse($response);
    }
}