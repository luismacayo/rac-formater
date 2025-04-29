<?php

namespace luismacayo\RacFormater\common\domain;

use luismacayo\RacFormater\common\domain\exceptions\BusinessUnavailableException;
use luismacayo\RacFormater\common\domain\resilience\CircuitBreakerInterface;
use luismacayo\RacFormater\common\infrastructure\middleware\CircuitBreakerMiddleware;
use luismacayo\RacFormater\common\infrastructure\monitoring\PerformanceTrackerInterface;
use luismacayo\RacFormater\common\infrastructure\RequestInterface;

class BusinessTrackerProviderDecorator implements ProviderInterface
{
    private ProviderInterface $provider;
    private PerformanceTrackerInterface $tracker;
    private CircuitBreakerInterface $circuitBreaker;

    /**
     * @param ProviderInterface $provider
     * @param PerformanceTrackerInterface $tracker
     * @param CircuitBreakerInterface $circuitBreaker
     */
    public function __construct(ProviderInterface $provider, PerformanceTrackerInterface $tracker, CircuitBreakerInterface $circuitBreaker)
    {
        $this->provider = $provider;
        $this->tracker = $tracker;
        $this->circuitBreaker = $circuitBreaker;
    }


    function configure(array $config): void
    {
        // TODO: Implement configure() method.
    }

    function getName(): string
    {
        return $this->provider->getName();
    }

    function isValidRequest(mixed $request): bool
    {
        $result = $this->provider->isValidRequest($request);
        if(!$result) {
            $this->circuitBreaker->recordFailure($this->getName());
        } else {
            $this->circuitBreaker->recordSuccess($this->getName());
        }
        return $result;
    }

    function isValidResponse(mixed $response): bool
    {
        $result = $this->provider->isValidResponse($response);
        if(!$result) {
            $this->circuitBreaker->recordFailure($this->getName());
        } else {
            $this->circuitBreaker->recordSuccess($this->getName());
        }
        return $result;
    }

    /**
     * @throws BusinessUnavailableException
     */
    function buildTemperatureRequest(mixed $request): RequestInterface
    {
        if(!$this->isValidRequest($request)) {
            throw new BusinessUnavailableException("Unvalid request for provider: {$this->getName()}");
        }
        $startTime = microtime(true);
        $response = $this->provider->buildTemperatureRequest($request);
        $duration = microtime(true) - $startTime;
        $this->tracker->recordMetric("provider.{$this->getName()}.generateRequest.duration", $duration);
        return $response;
    }

    function transformResponse(mixed $response): mixed
    {
        $startTime = microtime(true);
        $response = $this->provider->transformResponse($response);
        $duration = microtime(true) - $startTime;
        $this->tracker->recordMetric("provider.{$this->getName()}.transformResponse.duration", $duration);
        return $response;
    }
}