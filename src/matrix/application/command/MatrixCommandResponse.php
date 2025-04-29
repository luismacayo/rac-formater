<?php

namespace luismacayo\RacFormater\matrix\application\command;

use luismacayo\RacFormater\common\bus\CommandResponse;

class MatrixCommandResponse implements CommandResponse
{
    public function __construct(
        public string $success,
        public mixed $results,
    ) {
    }
}