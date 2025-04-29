<?php

namespace luismacayo\RacFormater\common\infrastructure\monitoring;

class DefaultPerformanceTracker implements PerformanceTrackerInterface
{
    private array $counters = [];
    private array $gauges = [];
    private array $histograms = [];
    private array $timers = [];

    public function recordMetric(string $name, $value): void
    {
        if (is_numeric($value)) {
            $this->histograms[$name][] = $value;
        }
        codecept_debug("Metric recorded: $name = $value");
    }

    public function incrementCounter(string $name, int $amount = 1): void
    {
        if (!isset($this->counters[$name])) {
            $this->counters[$name] = 0;
        }

        $this->counters[$name] += $amount;
    }

    public function startTimer(string $name): string
    {
        $id = uniqid($name . '_', true);
        $this->timers[$id] = [
            'name' => $name,
            'start' => microtime(true)
        ];

        return $id;
    }

    public function stopTimer(string $timerId): float
    {
        if (!isset($this->timers[$timerId])) {
            throw new \InvalidArgumentException("Timer with ID $timerId not found");
        }

        $timer = $this->timers[$timerId];
        $duration = microtime(true) - $timer['start'];

        $this->recordMetric($timer['name'] . '.duration', $duration);

        unset($this->timers[$timerId]);

        return $duration;
    }

    public function exportMetrics(): array
    {
        return [
            'counters' => $this->counters,
            'histograms' => $this->histograms,
            'gauges' => $this->gauges
        ];
    }
}