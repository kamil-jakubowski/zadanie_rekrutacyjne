<?php
declare(strict_types=1);

namespace App\Cart\Application\Command\CreateCart;

use App\Cart\Domain\Cart;
use App\Cart\Domain\CartRepositoryInterface;

/**
 * @author Kamil Jakubowski <kamil.jakubowski@gmail.com>
 */
class CreateCartHandler implements CreateCartHandlerInterface
{
    private CartRepositoryInterface $cartRepository;

    /**
     * @param CartRepositoryInterface $cartRepository
     */
    public function __construct(CartRepositoryInterface $cartRepository)
    {
        $this->cartRepository = $cartRepository;
    }

    public function __invoke(CreateCartCommand $cmd): void
    {
        $cart = Cart::createNew([]);
        $this->cartRepository->createNewCart($cart);
        $cmd->setNewResourceUuid($cart->getUuid()->toString());
    }
}