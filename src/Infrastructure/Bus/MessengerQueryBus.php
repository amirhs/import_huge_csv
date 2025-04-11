<?php
// src/Infrastructure/Bus/MessengerQueryBus.php
namespace App\Infrastructure\Bus;

use App\Application\Bus\QueryBusInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Messenger\Exception\HandlerFailedException;

class MessengerQueryBus implements QueryBusInterface
{
    private MessageBusInterface $queryBus;

    public function __construct(MessageBusInterface $queryBus)
    {
        $this->queryBus = $queryBus;
    }

    public function handle(object $query): mixed
    {
        try {
            $envelope = $this->queryBus->dispatch($query);
            
            /** @var HandledStamp $handledStamp */
            $handledStamp = $envelope->last(HandledStamp::class);
            
            return $handledStamp->getResult();
        } catch (HandlerFailedException $e) {
            // Unwrap the exception
            throw $e->getPrevious() ?? $e;
        }
    }
}
