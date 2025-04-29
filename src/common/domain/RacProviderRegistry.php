<?php

namespace luismacayo\RacFormater\common\domain;

final class RacProviderRegistry implements ProviderRegistryInterface
{
    private array $providers = [];

    private array $installedProviders = [];

    private array $configurations = [];

    public function register(string $name, string $className, array $configuration): void
    {
        if (isset($this->providers[$name])) {
            throw new \InvalidArgumentException("Provider already registered: $name");
        }
        $this->providers[$name] = $className;
        $this->configurations[$name] = $configuration;
    }

    public function addProvider(string $name, ProviderInterface $provider): void
    {
        $this->installedProviders[$name] = $provider;
    }

    public function getAll(): array
    {
        foreach ($this->providers as $name => $className) {
            if (isset($this->providers[$name])) {
                if (class_exists($className)) {
                    $provider = new $className();
                    $provider->configure($this->configurations[$name]);
                    $this->installedProviders[] = $provider;
                }else{
                    throw new \InvalidArgumentException("Class not found: $className");
                }
            }
        }
        return $this->installedProviders;
    }

    public function getByName(string $name): ProviderInterface
    {
        if (isset($this->installedProviders[$name])) {
            return $this->installedProviders[$name];
        }
        if (!isset($this->providers[$name])) {
            throw new \InvalidArgumentException("Provider not registered: $name");
        }
        $className = $this->providers[$name];
        if (!class_exists($className)) {
            throw new \InvalidArgumentException("Class not found: $className");
        }
        $provider = new $className();
        $provider->configure($this->configurations[$name]);
        return $provider;
    }
}