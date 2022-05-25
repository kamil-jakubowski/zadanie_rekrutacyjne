<?php

namespace App\Tests\Intergration\Product\Application\Command\CreateProduct;

use App\Product\Application\Command\CreateProduct\CreateProductCommand;
use App\Product\Domain\Product;
use App\Product\Domain\ProductRepositoryInterface;
use App\Shared\CQRS\Command\CommandBusInterface;
use App\Shared\Domain\ValueObject\Uuid;
use App\Tests\Helper\ProductTestHelper;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CreateProductHandlerTest extends KernelTestCase
{
    use ProductTestHelper;

    protected CommandBusInterface $commandBus;
    protected ProductRepositoryInterface $productRepository;

    public function setUp(): void
    {
        $kernel = static::bootKernel();

        $this->commandBus = static::getContainer()->get(CommandBusInterface::class);
        $this->productRepository = static::getContainer()->get(ProductRepositoryInterface::class);
    }

    public function testSuccessfulCreation(): void
    {
        // Given
        $name = $this->generateValidProductName();
        $command = new CreateProductCommand($name, $this->generateValidPrice());

        // When
        $this->commandBus->dispatch($command);

        // Then
        $uuid = $command->getNewResourceUuid();
        $this->assertIsString($uuid);
        $tryProduct = $this->productRepository->findProductByUuid(Uuid::fromString($uuid));
        $this->assertEquals($tryProduct->getUuid()->toString(), $uuid);
        $this->assertEquals($tryProduct->getName(), $name);
    }
}
