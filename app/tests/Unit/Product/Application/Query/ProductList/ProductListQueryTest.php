<?php

namespace App\Tests\Unit\Product\Application\Query\ProductList;

use App\Product\Application\Query\ProductList\Exception\MaxProductPerPageExceededException;
use App\Product\Application\Query\ProductList\Exception\WrongPageNumberException;
use App\Product\Application\Query\ProductList\ProductListQuery;
use App\Tests\Helper\ProductTestHelper;
use PHPUnit\Framework\TestCase;

class ProductListQueryTest extends TestCase
{
    use ProductTestHelper;

    public function testCreateQueryShouldSuccess(): void
    {
        // Given
        $validPage = 1;
        $validItems = ProductListQuery::MAX_PER_PAGE;

        // When
        $query = new ProductListQuery($validPage, $validItems);

        // Then
        $this->assertSame($query->getPage(), $validPage);
        $this->assertSame($query->getItemsPerPage(), $validItems);
    }

    public function testCreateQueryWithInValidPageShouldFaild(): void
    {
        // Given
        $invalidPage = -1;
        $validItems = ProductListQuery::MAX_PER_PAGE;

        // Then
        $this->expectException(WrongPageNumberException::class);

        // When
        $query = new ProductListQuery($invalidPage, $validItems);
    }

    public function testCreateQueryWithInValidItemsPerPageShouldFaild(): void
    {
        // Given
        $validPage = 1;
        $invalidItems = ProductListQuery::MAX_PER_PAGE+1;

        // Then
        $this->expectException(MaxProductPerPageExceededException::class);

        // When
        $query = new ProductListQuery($validPage, $invalidItems);
    }
}
