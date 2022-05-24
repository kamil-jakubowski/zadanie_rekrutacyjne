<?php

namespace App\Tests\Unit\Cart\Domain;

use App\Cart\Domain\CartProduct;
use App\Cart\Domain\Exception\InvalidCartProductQuantityException;
use App\Product\Domain\Product;
use PHPUnit\Framework\TestCase;

/**
 * @todo to delete
 * @deprecated IMPORTANT see CartProduct class explanation
 * Finally unsed I leave it to eventual discussion
 */
class CartProductTest extends TestCase
{
    public function testCreateCartProductSuccess(): void
    {
        // Given
        $product = $this->createStub(Product::class);

        // When
        $cartProduct = CartProduct::create($product);

        // Then
        $this->assertSame($product, $cartProduct->getProduct());
        $this->assertSame(1, $cartProduct->getQuantity());
    }

    public function testChangeCartProductQuantity(): void
    {
        // Given
        $product = $this->createStub(Product::class);
        $cartProduct = CartProduct::create($product);
        $quantity = 5;

        // When
        $cartProduct->changeProductQuantity($quantity);

        // Then
        $this->assertSame($cartProduct->getQuantity($product), 5);
    }

    public function testChangeCartProductQuantityWithNegativeShouldFail(): void
    {
        // Given
        $product = $this->createStub(Product::class);
        $cartProduct = CartProduct::create($product);

        // Then
        $this->expectException(InvalidCartProductQuantityException::class);

        // When
        $cartProduct->changeProductQuantity(-1);
    }

    public function testChangeCartProductQuantityWithZeroShouldFail(): void
    {
        // Given
        $product = $this->createStub(Product::class);
        $cartProduct = CartProduct::create($product);

        // Then
        $this->expectException(InvalidCartProductQuantityException::class);

        // When
        $cartProduct->changeProductQuantity(0);
    }


    public function testIncreaseCartProductQuantitySuccess(): void
    {
        // Given
        $product = $this->createStub(Product::class);
        $cartProduct = CartProduct::create($product);
        $quantity = $cartProduct->getQuantity();
        $increase = rand(2,10);

        // When
        $cartProduct->increaseProductQuantity($increase);

        // Then
        $this->assertSame($cartProduct->getQuantity($product), $quantity += $increase);
    }

    public function testIncreaseCartProductQuantityWithNegativeShouldFail(): void
    {
        // Given
        $product = $this->createStub(Product::class);
        $cartProduct = CartProduct::create($product);

        // Then
        $this->expectException(InvalidCartProductQuantityException::class);

        // When
        $cartProduct->increaseProductQuantity(-1);
    }

    public function testIncreaseCartProductQuantityWithZeroShouldFail(): void
    {
        // Given
        $product = $this->createStub(Product::class);
        $cartProduct = CartProduct::create($product);

        // Then
        $this->expectException(InvalidCartProductQuantityException::class);

        // When
        $cartProduct->increaseProductQuantity(0);
    }
}
