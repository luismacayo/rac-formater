<?php

namespace luismacayo\RacFormater\common\infrastructure;

class CurlRepositoryFactory implements RepositoryFactoryInterface
{
    public function createRequestExecutor(): RequestExecutorInterface
    {
        return new CurlRequestExecutor();
    }
}