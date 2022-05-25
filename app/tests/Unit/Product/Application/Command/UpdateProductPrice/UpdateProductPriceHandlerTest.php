<?php

namespace App\Tests\Unit\Product\Application\Command\UpdateProductName;

use App\Product\Application\Command\Exception\ProductNotFoundException;
use App\Product\Application\Command\UpdateProductPrice\UpdateProductPriceCommand;
use App\Product\Application\Command\UpdateProductPrice\UpdateProductPriceHandler;
use App\Product\Domain\ProductRepositoryInterface;
use App\Shared\Domain\ValueObject\Uuid;
use App\Tests\Helper\ProductTestHelper;
use PHPUnit\Framework\TestCase;

class UpdateProductPriceHandlerTest extends TestCase
{
    use ProductTestHelper;

    public function testUpdateShouldWithUnExistedUuidShouldFail(): void
    {
        // given
        $uuid = Uuid::random();
        $newPrice = $this->generateValidPrice();
        $command = new UpdateProductPriceCommand($uuid->toString(), $newPrice);
        $productRepositoryMock = $this->createMock(ProductRepositoryInterface::class);
        $handler = new UpdateProductPriceHandler($productRepositoryMock);

        // then
        $productRepositoryMock->expects($this->once())
            ->method('findProductByUuid')
            ->willReturn(null);

        $this->expectException(ProductNotFoundException::class);

        // when
        $handler($command);
    }

    public function testUpdateShouldSuccess(): void
    {
        // given
        $product = $this->getNewProduct();
        $uuid = $product->getUuid();
        $newPrice = $this->generateValidPrice();
        $command = new UpdateProductPriceCommand($uuid->toString(), $newPrice);
        $productRepositoryMock = $this->createMock(ProductRepositoryInterface::class);
        $handler = new UpdateProductPriceHandler($productRepositoryMock);

        // then
        $productRepositoryMock->expects($this->once())
            ->method('findProductByUuid')
            ->willReturn($product);

        $productRepositoryMock->expects($this->once())
            ->method('updateProduct');

        // when
        $handler($command);
    }
}
