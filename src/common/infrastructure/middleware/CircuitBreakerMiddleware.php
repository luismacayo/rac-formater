<?php

namespace luismacayo\RacFormater\common\infrastructure\middleware;

use luismacayo\RacFormater\common\domain\resilience\CircuitBreakerInterface;
use luismacayo\RacFormater\common\infrastructure\exceptions\ServiceUnavailableException;
use luismacayo\RacFormater\common\infrastructure\RequestInterface;

class CircuitBreakerMiddleware implements RequestMiddlewareInterface
{
    private CircuitBreakerInterface $circuitBreaker;

    public function __construct(CircuitBreakerInterface $circuitBreaker)
    {
        $this->circuitBreaker = $circuitBreaker;
    }

    /**
     * @throws ServiceUnavailableException
     */
    public function process(RequestInterface $request, callable $next)
    {
        $service = parse_url($request->getUrl(), PHP_URL_HOST);

        if (!$this->circuitBreaker->isAvailable($service)) {
            return [
                'error' => 'Service unavailable'
            ];
        }
        try {
            $response = $next($request);
            $this->circuitBreaker->recordSuccess($service);
            codecept_debug("Success in CircuitBreakerMiddleware: " . $service);
            return $response;
        } catch (\Exception $e) {
            $this->circuitBreaker->recordFailure($service);
            codecept_debug("Error in CircuitBreakerMiddleware: " . $service);
            throw $e;
        }
    }
}