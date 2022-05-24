<?php

namespace App\Tests\Intergration\Cart\Infrastructure\Doctrine\Repository;

use App\Cart\Domain\Cart;
use App\Cart\Infrastructure\Doctrine\Repository\CartRepository;
use App\Product\Domain\Product;
use App\Product\Infrastructure\Doctrine\Repository\ProductRepository;
use App\Tests\Helper\ProductTestHelper;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Based on clean database with fixtures from \App\DataFixtures\CartFixtures
 */
class CartRepositoryTest extends KernelTestCase
{
    use ProductTestHelper;

    protected CartRepository $cartRepository;

    protected EntityManager $em;

    public function setUp(): void
    {
        $kernel = static::bootKernel();

        $this->cartRepository = static::getContainer()->get(CartRepository::class);
        $this->em = static::getContainer()->get('doctrine')->getManager();
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

    public function testFindCartByUuidShouldSuccess()
    {
        // Given
        $carts = $this->cartRepository->findAll();
        $cart = $carts[0];
        $id = $cart->getId();
        $uuid = $cart->getUuid();

        // When
        $foundCart = $this->cartRepository->findCartByUuid($uuid);

        // Then
        $this->assertNotNull($foundCart);
        $this->assertSame($foundCart->getId(), $id);
    }

    public function testRemoveCartShouldSuccess()
    {
        // Given
        $carts = $this->cartRepository->findAll();
        $cart = $carts[0];
        $id = $cart->getId();
        $uuid = $cart->getUuid();

        // When
        $this->cartRepository->removeCart($cart);

        // Then
        $cart = $this->cartRepository->find($id);
        $this->assertFalse($cart instanceof Cart);
    }

    public function testCreateNewCartShouldSuccess()
    {
        // Given
        $product = Product::createNew($this->generateValidProductName(), $this->generateValidPrice());
        $cart = Cart::createNew([$product]);

        // When
        $this->cartRepository->createNewCart($cart);

        // Then
        $this->assertNotNull($cart->getId());
        $this->assertIsArray($cart->getProducts());
        $this->assertContains($product, $cart->getProducts());
    }

    public function testAddProductsToCartShouldSuccess()
    {
        // Given
        $product = Product::createNew($this->generateValidProductName(), $this->generateValidPrice());
        $cart = Cart::createNew([$product]);
        $this->cartRepository->createNewCart($cart);
        $quantityProducts = count($cart->getProducts());
        $cartId = $cart->getId();

        $newProduct = Product::createNew($this->generateValidProductName(), $this->generateValidPrice());

        // When
        $cart->addProduct($newProduct);
        $this->cartRepository->updateCart($cart);

        // Then
        $this->em->clear();
        /** @var Cart $cart */
        $cart = $this->cartRepository->find($cartId);

        $this->assertIsObject($cart);
        $this->assertSame(count($cart->getProducts()), $quantityProducts+1);
    }

    public function testRemoveProductFromCartShouldSuccess()
    {
        // Given
        $carts = $this->cartRepository->findAll();
        /** @var Cart $cart */
        $cart = $carts[0];
        $id = $cart->getId();
        $uuid = $cart->getUuid();
        $quantityOfProducts = count($cart->getProducts());
        $products = $cart->getProducts();

        // When
        $cart->removeProduct($products[1]);
        $this->cartRepository->updateCart($cart);

        // Then
        $this->em->clear();
        /** @var Cart $cart */
        $cart = $this->cartRepository->find($id);

        $this->assertIsObject($cart);
        $this->assertSame(count($cart->getProducts()), $quantityOfProducts-1);
    }
}
