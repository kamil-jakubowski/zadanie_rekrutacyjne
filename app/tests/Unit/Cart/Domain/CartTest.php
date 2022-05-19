<?php

namespace App\Tests\Unit\Cart\Domain;

use App\Cart\Domain\Cart;

use App\Cart\Domain\CartProduct;
use App\Cart\Domain\Collection\CartProductCollection;
use App\Product\Domain\Collection\ProductCollection;
use App\Product\Domain\Product;
use PHPUnit\Framework\TestCase;

class CartTest extends TestCase
{
    public function testCreateEmptyCartSuccess(): void
    {
        // Given
        //none

        // When
        $cart = Cart::createNew([]);

        // Then
        $this->assertEquals(new ProductCollection([]), $cart->getProducts());
    }
}
