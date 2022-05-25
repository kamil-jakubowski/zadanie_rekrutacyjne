<?php

namespace App\Tests\Unit\Cart\Application\Command\RemoveProduct;

use App\Cart\Application\Command\RemoveProduct\RemoveProductCommand;
use App\Cart\Application\Command\RemoveProduct\RemoveProductHandler;
use App\Cart\Domain\Cart;
use App\Cart\Domain\CartRepositoryInterface;
use App\Product\Domain\ProductRepositoryInterface;
use App\Tests\Helper\ProductTestHelper;
use PHPUnit\Framework\TestCase;

class RemoveProductHandlerTest extends TestCase
{
    use ProductTestHelper;

    public function testRemoveProductFromCartSuccess(): void
    {
        // Given
        $someProduct = $this->getNewProduct();
        $someCart = Cart::createNew([$someProduct]);
        $cartRepositoryMock = $this->createMock(CartRepositoryInterface::class);
        $productRepositoryMock = $this->createMock(ProductRepositoryInterface::class);
        $handler = new RemoveProductHandler($cartRepositoryMock, $productRepositoryMock);
        $command = new RemoveProductCommand($someCart->getUuid()->toString(), $someProduct->getUuid()->toString());

        // Then
        $productRepositoryMock->expects($this->once())
            ->method('findProductByUuid')
            ->willReturn($someProduct);

        $cartRepositoryMock->expects($this->once())
            ->method('findCartByUuid')
            ->willReturn($someCart);

        $cartRepositoryMock->expects($this->once())
            ->method('updateCart')
            ->with($someCart);

        // When
        $handler($command);
    }
}
