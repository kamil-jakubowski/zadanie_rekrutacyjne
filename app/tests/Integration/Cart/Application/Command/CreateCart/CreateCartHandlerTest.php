<?php
declare(strict_types=1);

namespace App\Tests\Intergration\Cart\Application\Command\CreateCart;

use App\Cart\Application\Command\CreateCart\CreateCartCommand;
use App\Cart\Domain\CartRepositoryInterface;
use App\Shared\CQRS\Command\CommandBusInterface;
use App\Shared\Domain\ValueObject\Uuid;
use App\Tests\Helper\ProductTestHelper;
use Negotiation\Tests\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @author Kamil Jakubowski <kamil.jakubowski@gmail.com>
 */
class CreateCartHandlerTest extends KernelTestCase
{
    use ProductTestHelper;

    protected CommandBusInterface $commandBus;
    protected CartRepositoryInterface $cartRepository;

    public function setUp(): void
    {
        $kernel = static::bootKernel();

        $this->commandBus = static::getContainer()->get(CommandBusInterface::class);
        $this->cartRepository = static::getContainer()->get(CartRepositoryInterface::class);
    }

    public function testSuccessfulCreation(): void
    {
        // Given
        $command = new CreateCartCommand();

        // When
        $this->commandBus->dispatch($command);

        // Then
        $uuid = $command->getNewResourceUuid();
        $this->assertIsString($uuid);
        $tryCart = $this->cartRepository->findCartByUuid(Uuid::fromString($uuid));
        $this->assertEquals($tryCart->getUuid()->toString(), $uuid);
    }
}