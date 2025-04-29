<?php

namespace luismacayo\RacFormater\common\infrastructure\monitoring;

interface PerformanceTrackerInterface
{
    public function recordMetric(string $name, $value): void;

    public function incrementCounter(string $name, int $amount = 1): void;

    public function startTimer(string $name): string;

    public function stopTimer(string $timerId): float;
}