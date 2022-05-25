<?php

namespace App\Tests\Unit\Cart\Application\Command\AddProduct;

use App\Cart\Application\Command\AddProduct\AddProductToCartCommand;
use App\Cart\Application\Command\AddProduct\AddProductToCartHandler;
use App\Cart\Domain\Cart;
use App\Cart\Domain\CartRepositoryInterface;
use App\Product\Domain\ProductRepositoryInterface;
use App\Tests\Helper\ProductTestHelper;
use PHPUnit\Framework\TestCase;

class AddProductToCartHandlerTest extends TestCase
{
    use ProductTestHelper;

    public function testAddingProductToCartSuccess(): void
    {
        // Given
        $someProduct = $this->getNewProduct();
        $someCart = Cart::createNew();
        $cartRepositoryMock = $this->createMock(CartRepositoryInterface::class);
        $productRepositoryMock = $this->createMock(ProductRepositoryInterface::class);
        $handler = new AddProductToCartHandler($cartRepositoryMock, $productRepositoryMock);
        $command = new AddProductToCartCommand($someCart->getUuid()->toString(), $someProduct->getUuid()->toString());

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
