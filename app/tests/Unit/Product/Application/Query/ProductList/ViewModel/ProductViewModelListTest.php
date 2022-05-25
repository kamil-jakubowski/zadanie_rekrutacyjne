<?php

namespace App\Tests\Unit\Product\Application\Query\ProductList\ViewModel;

use App\Product\Application\Query\ProductList\ViewModel\ProductViewModelList;
use App\Tests\Helper\ProductTestHelper;
use PHPUnit\Framework\TestCase;

class ProductViewModelListTest extends TestCase
{
    use ProductTestHelper;

    public function testCreateFromDomainProductsShouldSuccess(): void
    {
        // Given
        $product1 = $this->getNewProduct();
        $product2 = $this->getNewProduct();
        $products = [$product1, $product2];
        $allProductsInDb = 10;

        // When
        $list = ProductViewModelList::createFromDomainProducts($products, $allProductsInDb);

        // Then
        $this->assertCount(2, $list);
        $this->assertSame($list[0]->getUuid(), $product1->getUuid()->toString());
        $this->assertSame($list[1]->getUuid(), $product2->getUuid()->toString());
        $this->assertSame($list[1]->getUuid(), $product2->getUuid()->toString());
        $this->assertSame($list->getQuantityOfAll(), $allProductsInDb);
    }
}
