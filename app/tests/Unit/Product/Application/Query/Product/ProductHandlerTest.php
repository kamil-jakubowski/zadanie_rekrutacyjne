<?php

namespace App\Tests\Unit\Product\Application\Query\Product;

use App\Product\Application\Query\Product\ProductHandler;
use App\Product\Application\Query\Product\ProductQuery;
use App\Product\Domain\ProductRepositoryInterface;
use App\Tests\Helper\ProductTestHelper;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class ProductHandlerTest extends TestCase
{
    use ProductTestHelper;

    public function testFindProductShouldSuccess(): void
    {
        // Given
        $productRepositoryMock = $this->createMock(ProductRepositoryInterface::class);
        $handler = new ProductHandler($productRepositoryMock);
        $uuid = \App\Shared\Domain\ValueObject\Uuid::random();
        $query = new ProductQuery($uuid->toString());

        // Then
        $productRepositoryMock->expects($this->once())
            ->method('findProductByUuid');

        // When
        $handler($query);
    }
}
