<?php
// src/Infrastructure/Bus/SimpleCommandBus.php
namespace App\Infrastructure\Bus;

use App\Application\Bus\CommandBusInterface;
use App\Application\Bus\CommandHandlerInterface;
use Psr\Container\ContainerInterface;

class SimpleCommandBus implements CommandBusInterface
{
    private ContainerInterface $container;
    private array $handlers;

    public function __construct(ContainerInterface $container, array $handlers = [])
    {
        $this->container = $container;
        $this->handlers = $handlers;
    }

    public function dispatch(object $command)
    {
        $commandClass = get_class($command);
        $handlerClass = str_replace('Command', 'CommandHandler', $commandClass);
        
        // Use handler from map or try to resolve by convention
        $handlerId = $this->handlers[$commandClass] ?? $handlerClass;
        
        if (!$this->container->has($handlerId)) {
            throw new \Exception("No handler found for command {$commandClass}");
        }
        
        /** @var CommandHandlerInterface $handler */
        $handler = $this->container->get($handlerId);
        
        return $handler($command);
    }
}
