<?php

namespace luismacayo\RacFormater\common\infrastructure\middleware;

use luismacayo\RacFormater\common\infrastructure\monitoring\PerformanceTrackerInterface;
use luismacayo\RacFormater\common\infrastructure\RequestInterface;

class MetricsMiddleware implements RequestMiddlewareInterface
{
    private PerformanceTrackerInterface $tracker;

    public function __construct(PerformanceTrackerInterface $tracker)
    {
        $this->tracker = $tracker;
    }

    public function process(RequestInterface $request, callable $next)
    {
        $startTime = microtime(true);
        $domain = parse_url($request->getUrl(), PHP_URL_HOST);

        try {
            $response = $next($request);

            $duration = microtime(true) - $startTime;
            $this->tracker->recordMetric("http.request.$domain.duration", $duration);
            $this->tracker->recordMetric("http.request.$domain.success", 1);

            return $response;
        } catch (\Exception $e) {
            $duration = microtime(true) - $startTime;
            $this->tracker->recordMetric("http.request.$domain.duration", $duration);
            $this->tracker->recordMetric("http.request.$domain.error", 1);

            throw $e;
        }
    }
}
