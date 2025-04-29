<?php

namespace luismacayo\RacFormater\common\infrastructure\middleware;

use luismacayo\RacFormater\common\infrastructure\RequestExecutorInterface;
use luismacayo\RacFormater\common\infrastructure\RequestInterface;

class MiddlewareRequestExecutor implements RequestExecutorInterface
{
    private RequestExecutorInterface $executor;
    private array $middleware = [];

    public function __construct(RequestExecutorInterface $executor)
    {
        $this->executor = $executor;
    }

    public function addMiddleware(RequestMiddlewareInterface $middleware): self
    {
        $this->middleware[] = $middleware;
        return $this;
    }

    public function execute(RequestInterface $request)
    {
        $pipeline = $this->createPipeline();
        return $pipeline($request);
    }

    public function executeAll(array $requests): array
    {
        $results = [];
        $pipeline = $this->createPipeline();

        foreach ($requests as $key => $request) {
            try {
                $results[$key] = $pipeline($request);
            } catch (\Exception $e) {
                // Log exception but continue to process other requests
                $results[$key] = null;
            }
        }

        return $results;
    }

    private function createPipeline(): callable
    {
        // Create a reverse iterator to build the pipeline from inside out
        $middleware = array_reverse($this->middleware);

        // Start with the core executor
        $pipeline = function (RequestInterface $request) {
            return $this->executor->execute($request);
        };

        // Wrap the executor with each middleware
        foreach ($middleware as $mw) {
            $next = $pipeline;
            $pipeline = static function (RequestInterface $request) use ($mw, $next) {
                return $mw->process($request, $next);
            };
        }

        return $pipeline;
    }
}