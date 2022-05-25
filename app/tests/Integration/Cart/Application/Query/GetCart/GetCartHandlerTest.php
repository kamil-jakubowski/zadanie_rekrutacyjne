<?php
declare(strict_types=1);

namespace App\Tests\Intergration\Cart\Application\Query\GetCart;

use App\Cart\Application\Command\CreateCart\CreateCartCommand;
use App\Cart\Application\Query\GetCart\GetCartQuery;
use App\Cart\Application\Query\ViewModel\CartViewModel;
use App\Cart\Domain\CartRepositoryInterface;
use App\DataFixtures\CartFixtures;
use App\Shared\CQRS\Command\CommandBusInterface;
use App\Shared\CQRS\Query\QueryBusInterface;
use App\Shared\Domain\ValueObject\Uuid;
use App\Tests\Helper\ProductTestHelper;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Messenger\Exception\HandlerFailedException;

/**
 * @author Kamil Jakubowski <kamil.jakubowski@gmail.com>
 */
class GetCartHandlerTest extends KernelTestCase
{
    use ProductTestHelper;

    protected QueryBusInterface $queryBus;
    protected CommandBusInterface $commandBus;
    protected CartRepositoryInterface $cartRepository;

    public function setUp(): void
    {
        $kernel = static::bootKernel();

        $this->queryBus = static::getContainer()->get(QueryBusInterface::class);
        $this->commandBus = static::getContainer()->get(CommandBusInterface::class);
        $this->cartRepository = static::getContainer()->get(CartRepositoryInterface::class);
    }

    public function testGetNotExistingProductShouldSuccessReturningException(): void
    {
        // Given
        $query = new GetCartQuery(Uuid::random()->toString());

        // Then
        $this->expectException(HandlerFailedException::class);

        // When
        $this->queryBus->dispatch($query);
    }

    public function testGetExistingProductShouldSuccessReturningCartViewModel(): void
    {
        // Given
        // create a new cart
        $newCartCommand = new CreateCartCommand([]);
        $this->commandBus->dispatch($newCartCommand);
        $newCartUuid = $newCartCommand->getNewResourceUuid();
        $getCartCommand = new GetCartQuery($newCartUuid);

        // When
        /** @var CartViewModel $cartViewModel */
        $cartViewModel = $this->queryBus->dispatch($getCartCommand);

        // Then
        $this->assertTrue($cartViewModel instanceof CartViewModel);
        $this->assertCount(0, $cartViewModel->getProducts(),);
    }
}