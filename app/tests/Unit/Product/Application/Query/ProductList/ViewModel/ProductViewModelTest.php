<?php

namespace App\Tests\Unit\Product\Application\Query\ProductList\ViewModel;

use App\Product\Application\Query\ProductList\ViewModel\ProductViewModel;
use App\Product\Domain\Product;
use App\Tests\Helper\ProductTestHelper;
use PHPUnit\Framework\TestCase;

class ProductViewModelTest extends TestCase
{
    use ProductTestHelper;

    public function testViewModelCreationFromProductShouldSuccess(): void
    {
        // Given
        $product = $this->getNewProduct();

        // When
        $vmProduct = ProductViewModel::createFromDomainProduct($product);

        // Then
        $this->assertSame($vmProduct->getUuid(), $product->getUuid()->toString());
        $this->assertSame($vmProduct->getName(), $product->getName());
        $this->assertSame($vmProduct->getPrice(), $product->getPrice());
    }
}
