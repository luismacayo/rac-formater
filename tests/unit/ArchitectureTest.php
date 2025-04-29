<?php

namespace luismacayo\RacFormater\Tests\unit;

use luismacayo\RacFormater\common\domain\BusinessTrackerProviderDecorator;
use luismacayo\RacFormater\common\domain\RacProviderRegistry;
use luismacayo\RacFormater\common\domain\resilience\DefaultCircuitBreakerImplementation;
use luismacayo\RacFormater\common\infrastructure\CurlRequestExecutor;
use luismacayo\RacFormater\common\infrastructure\middleware\CircuitBreakerMiddleware;
use luismacayo\RacFormater\common\infrastructure\middleware\MetricsMiddleware;
use luismacayo\RacFormater\common\infrastructure\middleware\MiddlewareRequestExecutor;
use luismacayo\RacFormater\common\infrastructure\monitoring\DefaultPerformanceTracker;
use luismacayo\RacFormater\matrix\application\command\MatrixCommand;
use luismacayo\RacFormater\matrix\application\command\MatrixCommandHandler;
use luismacayo\RacFormater\providers\decorated\domain\WeatherDecoratedProvider;
use luismacayo\RacFormater\providers\duck\domain\DuckProvider;
use luismacayo\RacFormater\providers\duck\domain\DuckProviderFallback;
use luismacayo\RacFormater\providers\ProviderEnum;
use luismacayo\RacFormater\providers\weather\domain\WeatherProvider;
use luismacayo\RacFormater\providers\wttr\domain\WttrProvider;

class ArchitectureTest extends \Codeception\Test\Unit
{
    public function testGeneral()
    {
        $providerRegistry = new RacProviderRegistry();
        $providerRegistry->register(ProviderEnum::WEATHER_API->name, WeatherProvider::class, [
            'apiKey' => 'd3374b9f76494fee818221220252304'
        ]);
        $providerRegistry->register(ProviderEnum::WEATHER_DECORATED_API->name, WeatherDecoratedProvider::class, [
            'apiKey' => 'd3374b9f76494fee818221220252304'
        ]);
        $providerRegistry->register(ProviderEnum::WTTR_API->name, WttrProvider::class, [
        ]);
        $providerRegistry->register(ProviderEnum::DUCK_FALLBACK_API->name, DuckProviderFallback::class, [
            'apiKey' => 'd3374b9f76494fee818221220252304'
        ]);
        $requestExecutor = new CurlRequestExecutor();

        $commandHandler = new MatrixCommandHandler($providerRegistry, $requestExecutor);

        $command = new MatrixCommand([
            'city' => 'Medellin'
        ]);

        $response = $commandHandler($command);
        codecept_debug($response);
    }

    public function testWithMiddleware(){
        $performanceTracker = new DefaultPerformanceTracker();
        $circuitBreaker = new DefaultCircuitBreakerImplementation();
        $providerRegistry = new RacProviderRegistry();
        $providerRegistry->register(ProviderEnum::WEATHER_API->name, WeatherProvider::class, [
            'apiKey' => 'd3374b9f76494fee818221220252304'
        ]);
        $providerRegistry->register(ProviderEnum::WEATHER_DECORATED_API->name, WeatherDecoratedProvider::class, [
            'apiKey' => 'd3374b9f76494fee818221220252304'
        ]);
        $providerRegistry->register(ProviderEnum::WTTR_API->name, WttrProvider::class, [
        ]);
        $providerRegistry->register(ProviderEnum::DUCK_API->name, DuckProvider::class, [
        ]);

        $baseExecutor = new CurlRequestExecutor();
        $executorWithMiddleware = new MiddlewareRequestExecutor($baseExecutor);
        $executorWithMiddleware->addMiddleware(new MetricsMiddleware($performanceTracker));
        $executorWithMiddleware->addMiddleware(new CircuitBreakerMiddleware($circuitBreaker));

        $commandHandler = new MatrixCommandHandler($providerRegistry, $executorWithMiddleware);

        $command = new MatrixCommand([
            'city' => 'Medellin'
        ]);

        $response = $commandHandler($command);
        codecept_debug($response);

        $commandSecond = new MatrixCommand([
            'city' => 'Medellin'
        ]);

        $responseSecond = $commandHandler($commandSecond);
        codecept_debug($responseSecond);
    }

    public function testWithBusinessTraker(){
        $performanceTracker = new DefaultPerformanceTracker();
        $circuitBreaker = new DefaultCircuitBreakerImplementation();
        $providerRegistry = new RacProviderRegistry();

        $duckProvider = new DuckProvider();
        $duckProvider->configure([]);

        $weatherProvider = new WeatherProvider();
        $weatherProvider->configure([
            'apiKey' => 'd3374b9f76494fee818221220252304'
        ]);

        $providerRegistry->addProvider(ProviderEnum::DUCK_API->name,  new BusinessTrackerProviderDecorator($duckProvider, $performanceTracker, $circuitBreaker));
        $providerRegistry->addProvider(ProviderEnum::WEATHER_API->name,  new BusinessTrackerProviderDecorator($weatherProvider, $performanceTracker, $circuitBreaker));

        $baseExecutor = new CurlRequestExecutor();
        $executorWithMiddleware = new MiddlewareRequestExecutor($baseExecutor);
        $executorWithMiddleware->addMiddleware(new MetricsMiddleware($performanceTracker));
        $executorWithMiddleware->addMiddleware(new CircuitBreakerMiddleware($circuitBreaker));

        $commandHandler = new MatrixCommandHandler($providerRegistry, $executorWithMiddleware);

        $command = new MatrixCommand([
            'city' => 'Medellin'
        ]);

        $response = $commandHandler($command);
        codecept_debug($response);

        $commandSecond = new MatrixCommand([
            'city' => 'Medellin'
        ]);

        $responseSecond = $commandHandler($commandSecond);
        codecept_debug($responseSecond);
    }

}