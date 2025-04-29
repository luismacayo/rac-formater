<?php

namespace luismacayo\RacFormater\common\bus;

interface CommandHandler
{
    public function __invoke(Command $command): CommandResponse;
}