<?php

namespace App\Tests\Unit\Cart\Application\Query\GetCart;

use App\Cart\Application\Query\GetCart\GetCartHandler;
use App\Cart\Application\Query\GetCart\GetCartQuery;
use App\Cart\Domain\Cart;
use App\Cart\Domain\CartRepositoryInterface;
use App\Shared\CQRS\Query\QueryBusInterface;
use PHPUnit\Framework\TestCase;

class GetCartHandlerTest extends TestCase
{
    public function testFindProductShouldSuccess(): void
    {
        // Given
        $repositoryMock = $this->createMock(CartRepositoryInterface::class);
        $queryBusMock = $this->createMock(QueryBusInterface::class);
        $handler = new GetCartHandler($repositoryMock, $queryBusMock);
        $someCart = Cart::createNew();
        $uuid = $someCart->getUuid();
        $query = new GetCartQuery($uuid->toString());


        // then
        $repositoryMock->expects($this->once())
            ->method('findCartByUuid')
            ->willReturn($someCart);

        // When
        $handler($query);
    }
}
