<?php

namespace App\Tests\Intergration\Product\Application\Command\CreateProduct;

use App\DataFixtures\ProductFixtures;
use App\Product\Application\Command\CreateProduct\CreateProductCommand;
use App\Product\Application\Query\Product\ProductQuery;
use App\Product\Application\Query\ProductList\ViewModel\ProductViewModel;
use App\Product\Domain\Product;
use App\Product\Domain\ProductRepositoryInterface;
use App\Shared\CQRS\Query\QueryBusInterface;
use App\Shared\Domain\ValueObject\Uuid;
use App\Tests\Helper\ProductTestHelper;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ProductHandlerTest extends KernelTestCase
{
    use ProductTestHelper;

    protected QueryBusInterface $queryBus;
    protected ProductRepositoryInterface $productRepository;

    public function setUp(): void
    {
        $kernel = static::bootKernel();

        $this->queryBus = static::getContainer()->get(QueryBusInterface::class);
        $this->productRepository = static::getContainer()->get(ProductRepositoryInterface::class);
    }

    public function testFindNotExistingProductShouldSuccessReturningNull(): void
    {
        // Given
        $query = new ProductQuery(Uuid::random());

        // When
        $productViewModel = $this->queryBus->dispatch($query);

        // Then
        $this->assertNull($productViewModel);
    }

    public function testFindExistingProductShouldSuccessReturningProductViewModel(): void
    {
        // Given
        $existingProduct = $this->productRepository->findProductByName(ProductFixtures::PRODUCT_INFO[0]['name']);
        $query = new ProductQuery($existingProduct->getUuid()->toString());

        // When
        $productViewModel = $this->queryBus->dispatch($query);

        // Then
        $this->assertTrue($productViewModel instanceof ProductViewModel);
    }
}
