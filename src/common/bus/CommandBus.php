<?php

namespace luismacayo\RacFormater\common\bus;

final class CommandBus
{
    private array $handlers = [];

    public function register(string $commandClass, string $handlerClass): void
    {
        $this->handlers[$commandClass] = $handlerClass;
    }

    public function execute(Command $command): CommandResponse
    {
        $commandClass = get_class($command);
        if (!isset($this->handlers[$commandClass])) {
            throw new \RuntimeException("No handler registered for command: $commandClass");
        }

        $handlerClass = $this->handlers[$commandClass];
        $handler = new $handlerClass();
        return $handler($command);
    }


}