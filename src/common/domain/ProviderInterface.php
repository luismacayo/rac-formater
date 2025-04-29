<?php

namespace luismacayo\RacFormater\common\domain;

use luismacayo\RacFormater\common\infrastructure\RequestInterface;

interface ProviderInterface
{
    function configure(array $config): void;
    function getName(): string;
    function isValidRequest(mixed $request): bool;
    function isValidResponse(mixed $response): bool;
    function buildTemperatureRequest(mixed $request): RequestInterface;
    function transformResponse(mixed $response): mixed;
}