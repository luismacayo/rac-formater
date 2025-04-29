<?php

namespace luismacayo\RacFormater\common\domain;

interface ProviderRegistryInterface
{
    public function register(string $name, string $className, array $configuration): void;

    /**
     * @return ProviderInterface[]
     */
    public function getAll(): array;

    public function getByName(string $name): ProviderInterface;
}