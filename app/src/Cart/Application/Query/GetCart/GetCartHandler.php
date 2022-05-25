<?php
declare(strict_types=1);

namespace App\Cart\Application\Query\GetCart;

use App\Cart\Application\Query\Exception\CartNotFoundException;
use App\Cart\Application\Query\ViewModel\CartViewModel;
use App\Cart\Domain\CartRepositoryInterface;
use App\Product\Application\Query\ProductList\ViewModel\ProductViewModel;
use App\Shared\CQRS\Query\QueryBusInterface;
use App\Shared\Domain\ValueObject\Uuid;

/**
 * @author Kamil Jakubowski <kamil.jakubowski@gmail.com>
 */
class GetCartHandler implements GetCartHandlerInterface
{
    private CartRepositoryInterface $cartRepository;

    private QueryBusInterface $queryBus;

    /**
     * @param CartRepositoryInterface $cartRepository
     * @param QueryBusInterface $queryBus
     */
    public function __construct(CartRepositoryInterface $cartRepository, QueryBusInterface $queryBus)
    {
        $this->cartRepository = $cartRepository;
        $this->queryBus = $queryBus;
    }

    public function __invoke(GetCartQuery $query): CartViewModel
    {
        $cart = $this->cartRepository->findCartByUuid(Uuid::fromString($query->getUuid()));

        if (!$cart) {
            throw CartNotFoundException::create($query->getUuid());
        }

        $vmCart = new CartViewModel($cart->getUuid()->toString());

        // yeah I can create another products query, but we have only max 3 products in cart in spec soooo
        // also we could (and should) do some joins in repository to getProducts() without lazy loading and another db query, but I think it's not the time for that ;-)
        // not enough time :)
        foreach ($cart->getProducts() as $product) {
            $productViewModel = ProductViewModel::createFromDomainProduct($product);
            $vmCart->appendProductViewModel($productViewModel);
        }

        return $vmCart;
    }
}