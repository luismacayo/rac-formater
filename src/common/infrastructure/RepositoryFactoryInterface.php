<?php

namespace luismacayo\RacFormater\common\infrastructure;

interface RepositoryFactoryInterface
{
    public function createRequestExecutor(): RequestExecutorInterface;
}