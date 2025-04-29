<?php

namespace luismacayo\RacFormater\matrix\application\command;

use luismacayo\RacFormater\common\bus\Command;

final class MatrixCommand implements Command
{
    /**
     * @param array $parameters
     */
    public function __construct(
        public array $parameters
    ) {
    }
}