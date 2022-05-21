<?php

namespace App\Tests\Unit\Cart\Domain;

use App\Cart\Domain\Cart;

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
        $this->assertEquals([], $cart->getProducts());
    }
}
