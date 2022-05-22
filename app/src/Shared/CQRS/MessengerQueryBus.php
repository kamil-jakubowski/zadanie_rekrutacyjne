<?php
declare(strict_types=1);

namespace App\Shared\CQRS;

use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * Query bus for CQRS Queries handle. Based on Symfony Messenger
 * @author Kamil Jakubowski <kamil.jakubowski@gmail.com>
 */
class MessengerQueryBus implements \App\Application\Query\QueryBusInterface
{
    use HandleTrait {
        handle as dispatchQuery;
    }

    public function __construct(MessageBusInterface $queryBus)
    {
        $this->messageBus = $queryBus;
    }

    public function dispatch(QueryInterface $query)
    {
        return $this->dispatchQuery($query);
    }
}