<?php
declare(strict_types=1);

namespace App\Tests\Intergration\Cart\Domain;

use App\Cart\Domain\Cart;
use App\Cart\Domain\Exception\CartMaximumProductsReachedException;
use App\Cart\Domain\Exception\ProductAlreadyExistsInCartException;
use App\Product\Domain\Product;
use App\Tests\Helper\ProductTestHelper;
use PHPUnit\Framework\TestCase;

/**
 * Class {CartTest}
 * @author Kamil Jakubowski <kamil.jakubowski@gmail.com>
 */
class CartTest extends TestCase
{
    use ProductTestHelper;

    public function testCreateCartWithCartProductShouldSuccess(): void
    {
        // Given
        $product1 = Product::createNew('Product1', 2.82);
        $product2 = Product::createNew('Product2', 12.04);

        // When
        $cart = Cart::createNew([$product1, $product2]);

        // Then
        $this->assertTrue($cart->hasProductInCart($product1));
        $this->assertTrue($cart->hasProductInCart($product2));
        $this->assertSame($product1->getName(), $cart->getProduct($product1)->getName());
        $this->assertSame($product2->getName(), $cart->getProduct($product2)->getName());
        $this->assertSame($product1->getPrice(), $cart->getProduct($product1)->getPrice());
        $this->assertSame($product2->getPrice(), $cart->getProduct($product2)->getPrice());
    }

    public function testAddNotExistedProductToCartShouldSuccess(): void
    {
        //Given
        $product = Product::createNew('Product new', 234.0);
        $cart = Cart::createNew([Product::createNew("some different product", 1.11)]);

        // When
        $cart->addProduct($product);

        // Then
        $this->assertTrue($cart->hasProductInCart($product));
    }

    public function testAddAlreadyExistedProductInCartShouldFail(): void
    {
        // Given
        $product = Product::createNew('Product new', 234.0);
        $cart = Cart::createNew([$product]);

        // Then
        $this->expectException(ProductAlreadyExistsInCartException::class);

        // When
        $cart->addProduct($product);
    }

    public function testTryingToAddToCartMoreProductThanMaximumShouldFail(): void
    {
        // Given
        $products = [];
        for ($i = 0; $i < Cart::MAX_PRODUCTS_IN_CART; $i++) {
            $products[] = Product::createNew($this->generateValidProductName(), $this->generateValidPrice());
        }
        $cart = Cart::createNew($products);
        $newProduct = Product::createNew($this->generateValidProductName(), $this->generateValidPrice());

        // Then
        $this->expectException(CartMaximumProductsReachedException::class);

        // When
        $cart->addProduct($newProduct);
    }

    public function testRemoveExistingProductInCartShouldSuccess(): void
    {
        //Given
        $product = Product::createNew($this->generateValidProductName(), $this->generateValidPrice());
        $cart = Cart::createNew([$product]);

        // When
        $cart->removeProduct($product);

        // Then
        $this->assertFalse($cart->hasProductInCart($product));
    }
}