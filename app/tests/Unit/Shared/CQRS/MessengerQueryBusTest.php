<?php

namespace App\Tests\Unit\Shared\Infrastructure\CQRS;

use App\Shared\CQRS\Command\CommandInterface;
use App\Shared\CQRS\Query\QueryInterface;
use App\Shared\Infrastructure\CQRS\MessengerQueryBus;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\Exception\LogicException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Envelope;

class MessengerQueryBusTest extends TestCase
{
    private MessageBusInterface $symfonyMessengerMock;
    private MessengerQueryBus $queryBus;
    private QueryInterface $query;

    protected function setUp(): void
    {
        $this->symfonyMessengerMock = $this->getMockBuilder(MessageBusInterface::class)->getMock();
        $this->queryBus = new MessengerQueryBus($this->symfonyMessengerMock);
        $this->query = new class() implements QueryInterface {
        };
    }

    public function testAnonymousQueryThatIsDispatchedToSymfonyMessageBusWithNoDefinedHandlerForQueryShouldFail(): void
    {
        // @todo jakiś sensowny test, bardziej intergracyjny, bo tutaj przez to że QueryBus używa HandleTrait z Symfony Messengera to nie idzie tego sprawdzic jednostkowo, bo realnie juz w testowanej klasie wykonuje logike sprawdzenia czy taka anonimowa dummy query ma swoj handler zarejestrowany. zostawiam pusty aby sie wyswietlał w ostrzezeniach phpunit
        /*// Given
        $query = $this->query;


        // Then
        $this->expectException();
        $this->symfonyMessengerMock
            ->expects($this->once())
            ->method('dispatch')
            ->with($query)
            ->willReturn(new Envelope($query));

        // When
        $this->queryBus->dispatch($query);*/
    }
}
