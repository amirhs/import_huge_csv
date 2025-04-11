<?php
// src/Infrastructure/Bus/MessengerCommandBus.php
namespace App\Infrastructure\Bus;

use App\Application\Bus\CommandBusInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Exception\HandlerFailedException;

class MessengerCommandBus implements CommandBusInterface
{
    private MessageBusInterface $commandBus;

    public function __construct(MessageBusInterface $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function dispatch(object $command): void
    {
        try {
            $this->commandBus->dispatch($command);
        } catch (HandlerFailedException $e) {
            // Unwrap the exception
            throw $e->getPrevious() ?? $e;
        }
    }
}
