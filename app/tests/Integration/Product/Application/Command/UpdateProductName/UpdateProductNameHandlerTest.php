<?php

namespace App\Tests\Integration\Product\Application\Command\UpdateProductName;

use App\DataFixtures\ProductFixtures;
use App\Product\Application\Command\UpdateProductName\UpdateProductNameCommand;
use App\Product\Domain\ProductRepositoryInterface;
use App\Shared\CQRS\Command\CommandBusInterface;
use App\Shared\Domain\ValueObject\Uuid;
use App\Tests\Helper\ProductTestHelper;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Messenger\Exception\HandlerFailedException;

class UpdateProductNameHandlerTest extends KernelTestCase
{
    use ProductTestHelper;

    protected CommandBusInterface $commandBus;
    protected ProductRepositoryInterface $productRepository;
    protected EntityManagerInterface $em;

    public function setUp(): void
    {
        $kernel = static::bootKernel();

        $this->commandBus = static::getContainer()->get(CommandBusInterface::class);
        $this->productRepository = static::getContainer()->get(ProductRepositoryInterface::class);
        $this->em = static::getContainer()->get(EntityManagerInterface::class);
    }

    public function testSuccessfulUpdate(): void
    {
        // Given
        $newName = $this->generateValidProductName();
        $existingProduct = $this->productRepository->findProductByName(ProductFixtures::PRODUCT_INFO[0]['name']);
        $command = new UpdateProductNameCommand($existingProduct->getUuid()->toString(), $newName);
        $uuid = $existingProduct->getUuid();

        // When
        $this->commandBus->dispatch($command);

        // Then
        $this->em->clear(); // clear to get again
        $tryProduct = $this->productRepository->findProductByUuid($uuid);
        $this->assertEquals($tryProduct->getUuid()->toString(), $uuid->toString());
        $this->assertEquals($tryProduct->getName(), $newName);
    }

    public function testUnSuccessfulUpdateWhenProductNotExists(): void
    {
        // Given
        $newName = $this->generateValidProductName();
        $uuid = Uuid::random();
        $command = new UpdateProductNameCommand($uuid->toString(), $newName);

        // Then
        $this->expectException(HandlerFailedException::class);

        // When
        $this->commandBus->dispatch($command);
    }
}
