<?php
declare(strict_types=1);

namespace App\Tests\Intergration\Product\Application\Command\RemoveProduct;

use App\DataFixtures\ProductFixtures;
use App\Product\Application\Command\RemoveProduct\RemoveProductCommand;
use App\Product\Domain\ProductRepositoryInterface;
use App\Shared\CQRS\Command\CommandBusInterface;
use App\Shared\Domain\ValueObject\Uuid;
use App\Tests\Helper\ProductTestHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Messenger\Exception\HandlerFailedException;

/**
 * @author Kamil Jakubowski <kamil.jakubowski@gmail.com>
 */
class RemoveProductHandlerTest extends KernelTestCase
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
        $existingProduct = $this->productRepository->findProductByName(ProductFixtures::PRODUCT_INFO[0]['name']);
        $command = new RemoveProductCommand($existingProduct->getUuid()->toString());
        $uuid = $existingProduct->getUuid();

        // When
        $this->commandBus->dispatch($command);

        // Then
        $this->em->clear(); // clear to get again
        $tryProduct = $this->productRepository->findProductByUuid($uuid);
        $this->assertNull($tryProduct); // product no found after removal
    }

    public function testUnSuccessfulUpdateWhenProductNotExists(): void
    {
        // Given
        $uuid = Uuid::random();
        $command = new RemoveProductCommand($uuid->toString());

        // Then
        $this->expectException(HandlerFailedException::class);

        // When
        $this->commandBus->dispatch($command);
    }
}