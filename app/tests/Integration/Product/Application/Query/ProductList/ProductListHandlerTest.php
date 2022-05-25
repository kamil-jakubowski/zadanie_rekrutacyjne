<?php

namespace App\Tests\Integration\Product\Application\Query\ProductList;

use App\DataFixtures\ProductFixtures;
use App\Product\Application\Query\ProductList\ProductListHandler;
use App\Product\Application\Query\ProductList\ProductListQuery;
use App\Product\Application\Query\ProductList\ViewModel\ProductViewModelList;
use App\Product\Infrastructure\Doctrine\Repository\ProductRepository;
use App\Shared\CQRS\Query\QueryBusInterface;
use App\Tests\Helper\ProductTestHelper;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Based on ProductsFixtures
 */
class ProductListHandlerTest extends KernelTestCase
{
    use ProductTestHelper;

    private QueryBusInterface $queryBus;

    private ProductListQuery $query;

    public function setUp(): void
    {
        $kernel = static::bootKernel();

        $this->queryBus = static::getContainer()->get(QueryBusInterface::class);
    }

    /**
     * @return void
     */
    public function testExecuteQueryShouldSuccess()
    {
        // Given
        // 6 products in fixtures and test db
        $products = ProductFixtures::PRODUCT_INFO;
        $query1 = new ProductListQuery(1, 1); // we should only get first record on the list
        $expectedQuantity1 = 1;
        $query2 = new ProductListQuery(2, 2); // we should only get third and fourth record on the list
        $expectedQuantity2 = 2;
        $query3 = new ProductListQuery(6, 1); // we should only get last record on the list
        $expectedQuantity3 = 1;
        $query4 = new ProductListQuery(4, 3); // we should get empty list
        $expectedQuantity4 = 0;

        // When
        /** @var ProductViewModelList $list1 */
        $list1 = $this->queryBus->dispatch($query1);
        $list2 = $this->queryBus->dispatch($query2);
        $list3 = $this->queryBus->dispatch($query3);
        $list4 = $this->queryBus->dispatch($query4);

        // Then
        $this->assertEquals(count($products), $list1->getQuantityOfAll());

        $this->assertCount($expectedQuantity1, $list1);
        $this->assertEquals($list1[0]->getName(), $products[0]['name']);

        $this->assertCount($expectedQuantity2, $list2);
        $this->assertEquals($list2[0]->getName(), $products[2]['name']);
        $this->assertEquals($list2[1]->getName(), $products[3]['name']);

        $this->assertCount($expectedQuantity3, $list3);
        $this->assertEquals($list3[0]->getName(), $products[5]['name']);

        $this->assertCount($expectedQuantity4, $list4);
        $this->assertEmpty($list4);
    }

}
