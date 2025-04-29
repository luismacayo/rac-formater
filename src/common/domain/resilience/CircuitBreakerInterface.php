<?php

namespace luismacayo\RacFormater\common\domain\resilience;

interface CircuitBreakerInterface
{
    public function isAvailable(string $service): bool;

    public function recordSuccess(string $service): void;

    public function recordFailure(string $service): void;

    public function reset(string $service): void;
}