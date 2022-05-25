<?php
declare(strict_types=1);

namespace App\Cart\Application\Command\RemoveProduct;

use App\Cart\Application\Query\Exception\CartNotFoundException;
use App\Cart\Domain\CartRepositoryInterface;
use App\Product\Application\Command\Exception\ProductNotFoundException;
use App\Product\Domain\ProductRepositoryInterface;
use App\Shared\Domain\ValueObject\Uuid;

/**
 * @author Kamil Jakubowski <kamil.jakubowski@gmail.com>
 */
class RemoveProductHandler implements RemoveProductHandlerInterface
{
    private CartRepositoryInterface $cartRepository;
    private ProductRepositoryInterface $productRepository;

    /**
     * @param CartRepositoryInterface $cartRepository
     */
    public function __construct(CartRepositoryInterface $cartRepository, ProductRepositoryInterface $productRepository)
    {
        $this->cartRepository = $cartRepository;
        $this->productRepository = $productRepository;
    }

    public function __invoke(RemoveProductCommand $cmd): void
    {
        $productUuid = $cmd->getProductUuid();
        $cartUuid = $cmd->getCartUuid();

        $product = $this->productRepository->findProductByUuid(Uuid::fromString($productUuid));
        $cart = $this->cartRepository->findCartByUuid(Uuid::fromString($cartUuid));

        if (!$cart) {
            throw CartNotFoundException::create($cartUuid);
        }

        if (!$product) {
            throw ProductNotFoundException::create($productUuid);
        }


        $cart->removeProduct($product);
        $this->cartRepository->updateCart($cart);
    }


}