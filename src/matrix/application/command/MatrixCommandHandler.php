<?php

namespace luismacayo\RacFormater\matrix\application\command;

use luismacayo\RacFormater\common\bus\Command;
use luismacayo\RacFormater\common\bus\CommandHandler;
use luismacayo\RacFormater\common\bus\CommandResponse;
use luismacayo\RacFormater\common\domain\exceptions\BusinessUnavailableException;
use luismacayo\RacFormater\common\domain\ProviderRegistryInterface;
use luismacayo\RacFormater\common\infrastructure\exceptions\ServiceUnavailableException;
use luismacayo\RacFormater\common\infrastructure\RequestExecutorInterface;

final readonly class MatrixCommandHandler implements CommandHandler
{
    private ProviderRegistryInterface $providerRegistry;
    private RequestExecutorInterface $requestExecutor;

    public function __construct(
        ProviderRegistryInterface $providerRegistry,
        RequestExecutorInterface  $requestExecutor
    )
    {
        $this->providerRegistry = $providerRegistry;
        $this->requestExecutor = $requestExecutor;
    }

    /**
     * @param MatrixCommand $command
     * @return MatrixCommandResponse
     */
    public function __invoke(Command $command): CommandResponse
    {
        $providers = $this->providerRegistry->getAll();
        $requests = [];
        $results = [];

        foreach ($providers as $provider) {
            try {
                if ($provider->isValidRequest($command->parameters)) {
                    $requests[$provider->getName()] = $provider->buildTemperatureRequest($command->parameters);
                }
            }catch (\Exception $e){
                $results[$provider->getName()] = [
                    'error' => $e->getMessage()
                ];
            }
        }
        $responses = $this->requestExecutor->executeAll($requests);
        foreach ($responses as $providerName => $response) {
            try {
                $provider = $this->providerRegistry->getByName($providerName);
                if ($provider->isValidResponse($response)) {
                    $results[$providerName] = $provider->transformResponse($response);
                }else{
                    $results[$providerName] = [
                        'error' => 'Invalid response'
                    ];
                }
            }catch (\Exception $e){
                $results[$provider->getName()] = [
                    'error' => $e->getMessage()
                ];
            }
        }
        return new MatrixCommandResponse(true, $results);

    }
}