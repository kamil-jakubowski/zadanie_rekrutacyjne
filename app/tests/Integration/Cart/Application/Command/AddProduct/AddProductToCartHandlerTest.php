<?php
declare(strict_types=1);

namespace App\Tests\Intergration\Cart\Application\Command\CreateCart;

use App\Cart\Application\Command\AddProduct\AddProductToCartCommand;
use App\Cart\Domain\Cart;
use App\Cart\Domain\CartRepositoryInterface;
use App\DataFixtures\ProductFixtures;
use App\Product\Domain\ProductRepositoryInterface;
use App\Shared\CQRS\Command\CommandBusInterface;
use App\Tests\Helper\ProductTestHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @author Kamil Jakubowski <kamil.jakubowski@gmail.com>
 */
class AddProductToCartHandlerTest extends KernelTestCase
{
    use ProductTestHelper;

    protected CommandBusInterface $commandBus;
    protected CartRepositoryInterface $cartRepository;
    protected ProductRepositoryInterface $productRepository;
    protected EntityManagerInterface $em;

    public function setUp(): void
    {
        $kernel = static::bootKernel();

        $this->commandBus = static::getContainer()->get(CommandBusInterface::class);
        $this->cartRepository = static::getContainer()->get(CartRepositoryInterface::class);
        $this->productRepository = static::getContainer()->get(ProductRepositoryInterface::class);
        $this->em = static::getContainer()->get(EntityManagerInterface::class);
    }

    public function testSuccessfulAddProductToCart(): void
    {
        // Given
        $existingProduct = $this->productRepository->findProductByName(ProductFixtures::PRODUCT_INFO[0]['name']);
        $newCart = Cart::createNew();
        $this->cartRepository->createNewCart($newCart);
        $cartUuid = $newCart->getUuid();
        $command = new AddProductToCartCommand($newCart->getUuid()->toString(), $existingProduct->getUuid()->toString());

        // When
        $this->commandBus->dispatch($command);

        // Then
        $this->em->clear();
        $cart = $this->cartRepository->findCartByUuid($cartUuid);

        $this->assertCount(1, $cart->getProducts());
        $this->assertEquals($cart->getProducts()[0]->getUuid(), $existingProduct->getUuid());
    }
}