<?php
declare(strict_types=1);

namespace App\Shared\CQRS;

use App\Shared\CQRS\Command\CommandBusInterface;
use App\Shared\CQRS\Command\CommandInterface;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * Command bus for CQRS Commands handle. Based on Symfony Messenger
 * @author Kamil Jakubowski <kamil.jakubowski@gmail.com>
 */
class MessengerCommandBus implements CommandBusInterface
{
    private MessageBusInterface $messageBus;

    public function __construct(MessageBusInterface $commandBus)
    {
        $this->messageBus = $commandBus;
    }

    public function dispatch(CommandInterface $command): void
    {
        $this->messageBus->dispatch($command);
    }
}