<?php

namespace luismacayo\RacFormater\matrix\domain;

class TemperatureDTO
{
    public function __construct(
        public string $city,
        public float $temperature
    ) {
    }
}