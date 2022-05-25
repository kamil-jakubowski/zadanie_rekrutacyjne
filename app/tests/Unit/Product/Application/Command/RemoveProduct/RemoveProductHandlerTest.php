<?php

namespace App\Tests\Unit\Product\Application\Command\RemoveProduct;

use App\Product\Application\Command\Exception\ProductNotFoundException;
use App\Product\Application\Command\RemoveProduct\RemoveProductCommand;
use App\Product\Application\Command\RemoveProduct\RemoveProductHandler;
use App\Product\Domain\ProductRepositoryInterface;
use App\Shared\Domain\ValueObject\Uuid;
use App\Tests\Helper\ProductTestHelper;
use PHPUnit\Framework\TestCase;

class RemoveProductHandlerTest extends TestCase
{
    use ProductTestHelper;

    public function testUpdateShouldWithUnExistedUuidShouldFail(): void
    {
        // given
        $uuid = Uuid::random();
        $newName = $this->generateValidProductName();
        $command = new RemoveProductCommand($uuid->toString());
        $productRepositoryMock = $this->createMock(ProductRepositoryInterface::class);
        $handler = new RemoveProductHandler($productRepositoryMock);

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
        $newName = $this->generateValidProductName();
        $command = new RemoveProductCommand($uuid->toString());
        $productRepositoryMock = $this->createMock(ProductRepositoryInterface::class);
        $handler = new RemoveProductHandler($productRepositoryMock);

        // then
        $productRepositoryMock->expects($this->once())
            ->method('findProductByUuid')
            ->willReturn($product);

        $productRepositoryMock->expects($this->once())
            ->method('removeProduct');

        // when
        $handler($command);
    }
}
