<?php

namespace luismacayo\RacFormater\common\infrastructure;

interface RequestExecutorInterface
{
    public function execute(RequestInterface $request);

    public function executeAll(array $requests): array;
}