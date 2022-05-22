<?php

namespace App\Tests\Unit\Shared\Infrastructure\CQRS;

use App\Shared\CQRS\Command\CommandInterface;
use App\Shared\Infrastructure\CQRS\MessengerCommandBus;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Envelope;

class MessengerCommandBusTest extends TestCase
{
    private MessageBusInterface $symfonyMessengerMock;
    private MessengerCommandBus $commandBus;

    protected function setUp(): void
    {
        $this->symfonyMessengerMock = $this->getMockBuilder(MessageBusInterface::class)->getMock();
        $this->commandBus = new MessengerCommandBus($this->symfonyMessengerMock);
        $this->command = new class() implements CommandInterface {

        };
    }

    public function testCommandIsDispatchedToSymfonyMessageBusShouldSuccess(): void
    {
        // Given
        $command = $this->command;

        // Then
        $this->symfonyMessengerMock
            ->expects($this->once())
            ->method('dispatch')
            ->with($command)
            ->willReturn(new Envelope($command));

        // When
        $this->commandBus->dispatch($command);
    }
}
