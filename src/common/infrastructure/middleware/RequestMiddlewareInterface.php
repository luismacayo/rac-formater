<?php

namespace luismacayo\RacFormater\common\infrastructure\middleware;

use luismacayo\RacFormater\common\infrastructure\RequestInterface;

interface RequestMiddlewareInterface
{
    public function process(RequestInterface $request, callable $next);
}