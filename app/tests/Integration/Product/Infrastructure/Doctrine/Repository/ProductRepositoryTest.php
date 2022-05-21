<?php

namespace App\Tests\Integration\Product\Infrastructure\Doctrine\Repository;

use App\DataFixtures\ProductFixtures;
use App\Product\Domain\Product;
use App\Product\Infrastructure\Doctrine\Exception\TryingToAddAlreadyPersistedProductException;
use App\Product\Infrastructure\Doctrine\Exception\TryingToUpdateNotPersistedProductException;
use App\Product\Infrastructure\Doctrine\Repository\ProductRepository;
use App\Tests\Helper\ProductTestHelper;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Based on clean database wth fixtures from \App\DataFixtures\CartFixtures
 */
class ProductRepositoryTest extends KernelTestCase
{
    use ProductTestHelper;

    protected ProductRepository $productRepository;

    public function setUp(): void
    {
        $kernel = static::bootKernel();

        $this->productRepository = static::getContainer()->get("App\Product\Infrastructure\Doctrine\Repository\ProductRepository");
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * @return Product
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function testFindProductByNameShouldSuccess(): Product
    {
        // given
        $existingNameInFixtures = ProductFixtures::PRODUCT_INFO[1]['name'];

        // When
        $product = $this->productRepository->findProductByName($existingNameInFixtures);

        // Then
        $this->assertEquals($product->getName(), $existingNameInFixtures);

        return $product;
    }

    /**
     * @depends testFindProductByNameShouldSuccess
     * @return void
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function testFindProductByUuidShouldSuccess(Product $product): void
    {
        // given
        $uuid = $product->getUuid();

        // When
        $product2 = $this->productRepository->findProductByUuid($uuid);

        // Then
        $this->assertEquals($product->getUuid()->toString(), $product2->getUuid()->toString());
    }

    /**
     * @depends testFindProductByNameShouldSuccess
     * @return void
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function testFindProductByIdShouldSuccess(Product $product): void
    {
        // given
        $id = $product->getId();

        // When
        $product2 = $this->productRepository->findProductById($id);

        // Then
        $this->assertEquals($product->getUuid()->toString(), $product2->getUuid()->toString());
    }

    public function testAddProductShouldSuccess()
    {
        // Given
        $name = $this->generateValidProductName();
        $price = $this->generateValidPrice();

        // When
        $product = Product::createNew($name, $price);
        $this->productRepository->addProduct($product);

        // Then
        $this->assertNotNull($product->getId());
        $findProduct = $this->productRepository->findProductByName($name);
        $findProduct2 = $this->productRepository->findProductByUuid($product->getUuid());
        $this->assertEquals($findProduct->getId(), $product->getId());
        $this->assertEquals($findProduct2->getId(), $product->getId());
    }

    public function testAddProductTryingAddExistingPersistedProductShouldFail(): void
    {
        // Given
        $existingProduct = $this->productRepository->findProductByName(ProductFixtures::PRODUCT_INFO[1]['name']);

        // Then
        $this->expectException(TryingToAddAlreadyPersistedProductException::class);

        //When
        $this->productRepository->addProduct($existingProduct);
    }

    public function testUpdateProductShouldSuccess()
    {
        // Given
        $existingProduct = $this->productRepository->findProductByName(ProductFixtures::PRODUCT_INFO[2]['name']);
        $newName = $this->generateValidProductName();
        $newPrice = $this->generateValidPrice();

        // When
        $existingProduct->setName($newName);
        $existingProduct->setPrice($newPrice);
        $this->productRepository->updateProduct($existingProduct);

        // Then
        $findThatProductByNewName = $this->productRepository->findProductByName($newName);
        $this->assertEquals($findThatProductByNewName->getId(), $existingProduct->getId());
        $this->assertEquals($findThatProductByNewName->getName(), $existingProduct->getName());
        $this->assertEquals($findThatProductByNewName->getPrice(), $existingProduct->getPrice());
    }

    public function testUpdateProductTryingWithNotPersistedProductShouldFail(): void
    {
        // Given
        $name = $this->generateValidProductName();
        $price = $this->generateValidPrice();
        $newProduct = Product::createNew($name, $price);

        // Then
        $this->expectException(TryingToUpdateNotPersistedProductException::class);

        //When
        $this->productRepository->updateProduct($newProduct);
    }

    public function testRemoveProductShouldSuccess()
    {
        // Given
        $existingProduct = $this->productRepository->findProductByName(ProductFixtures::PRODUCT_INFO[2]['name']);
        $id = $existingProduct->getId();
        $uuid = $existingProduct->getUuid();

        // When
        $this->productRepository->removeProduct($existingProduct);

        // Then
        $findThatProductById = $this->productRepository->findProductById($id);
        $findThatProductByUuid = $this->productRepository->findProductByUuid($uuid);

        $this->assertNull($findThatProductById);
        $this->assertNull($findThatProductByUuid);
    }

    public function testRemoveProductTryingWithNotPersistedProductShouldFail(): void
    {
        // Given
        $name = $this->generateValidProductName();
        $price = $this->generateValidPrice();
        $newProduct = Product::createNew($name, $price);

        // Then
        $this->expectException(TryingToUpdateNotPersistedProductException::class);

        //When
        $this->productRepository->removeProduct($newProduct);
    }

    public function testGetListShouldSuccess(): void
    {
        // Given
        $offset = 1;
        $limit = 3;

        // When
        $list = $this->productRepository->getProductsList($offset, $limit);

        // then
        $this->assertIsArray($list);
        $firstProduct = $list[0];
        $this->assertCount(3, $list);
        $secondProductInTable = $this->productRepository->findProductByName(ProductFixtures::PRODUCT_INFO[1]['name']);
        $this->assertEquals($firstProduct->getId(), $secondProductInTable->getId());
    }
}
