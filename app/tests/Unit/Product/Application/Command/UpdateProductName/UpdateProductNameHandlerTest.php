<?php

namespace App\Tests\Unit\Product\Application\Command\UpdateProductName;

use App\Product\Application\Command\Exception\ProductNotFoundException;
use App\Product\Application\Command\UpdateProductName\UpdateProductNameCommand;
use App\Product\Application\Command\UpdateProductName\UpdateProductNameHandler;
use App\Product\Domain\ProductRepositoryInterface;
use App\Shared\Domain\ValueObject\Uuid;
use App\Tests\Helper\ProductTestHelper;
use PHPUnit\Framework\TestCase;

class UpdateProductNameHandlerTest extends TestCase
{
    use ProductTestHelper;

    public function testUpdateShouldWithUnExistedUuidShouldFail(): void
    {
        // given
        $uuid = Uuid::random();
        $newName = $this->generateValidProductName();
        $command = new UpdateProductNameCommand(Uuid::random()->toString(), $newName);
        $productRepositoryMock = $this->createMock(ProductRepositoryInterface::class);
        $handler = new UpdateProductNameHandler($productRepositoryMock);

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
        $command = new UpdateProductNameCommand($uuid->toString(), $newName);
        $productRepositoryMock = $this->createMock(ProductRepositoryInterface::class);
        $handler = new UpdateProductNameHandler($productRepositoryMock);

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
