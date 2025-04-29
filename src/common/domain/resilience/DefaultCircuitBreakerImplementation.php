<?php

namespace luismacayo\RacFormater\common\domain\resilience;

class DefaultCircuitBreakerImplementation implements CircuitBreakerInterface
{
    private array $serviceStates = [];
    public function isAvailable(string $service): bool
    {
        // Check if the service is available based on its state
        if (!isset($this->serviceStates[$service])) {
            $this->serviceStates[$service] = true; // Default to available
        }

        return $this->serviceStates[$service];
    }

    public function recordSuccess(string $service): void
    {
        // Record success for the service
        if (isset($this->serviceStates[$service])) {
            $this->serviceStates[$service] = true; // Set to available
        }
    }

    public function recordFailure(string $service): void
    {
        // Record failure for the service
        if (isset($this->serviceStates[$service])) {
            $this->serviceStates[$service] = false; // Set to unavailable
        }
    }

    public function reset(string $service): void
    {
        // Reset the service state
        if (isset($this->serviceStates[$service])) {
            $this->serviceStates[$service] = true; // Set to available
        }
    }
}