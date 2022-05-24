<?php
declare(strict_types=1);

namespace App\Cart\Domain;

use App\Product\Domain\Product;
use App\Cart\Domain\Exception\InvalidCartProductQuantityException;
use App\Shared\Domain\ValueObject\Uuid;

/**
 * @deprecated
 * Class represent product and its quantity in the cart
 * IMPORTANT: It's finally unused and should be deleted but I leave it for discussion - that class was created
 * because firstly I've misinterpreted the task specification, that was not 100% clear for me about it.
 * Normally I would ask about it the client (I can be fatiguing about it believe me), but due to lack of free time to do that task, time for waiting for an answer I've made a decision that interpretation that it could be only one product of the same identity in the cart (not many the same products like in every e-commerce) and I will leave that class to proof that I also assumed this variant :)
 * @author Kamil Jakubowski <kamil.jakubowski@gmail.com>
 */
class CartProduct
{
    private Product $product;

    /**
     * For better collection searching
     * @see self::getProductUuidString()
     * @var Uuid
     */
    private Uuid $productUuid;

    private int $quantity = 0;

    private function __construct(Product $product, int $quantity = 1)
    {
        $this->product = $product;
        $this->productUuid = $product->getUuid();
        $this->quantity = $quantity;
    }

    static public function create(Product $product, $quantity = 1): static
    {
        return new static($product, $quantity);
    }

    /**
     * @return Product
     */
    public function getProduct(): Product
    {
        return $this->product;
    }

    /**
     * For purposes of fast searching CartProductsCollection
     * @return string
     */
    public function getProductUuidString(): string
    {
        return $this->productUuid->toString();
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function changeProductQuantity(int $quantity): void
    {
        if ($quantity <= 0) {
            throw InvalidCartProductQuantityException::create($quantity);
        }

        $this->quantity = $quantity;
    }

    public function increaseProductQuantity(int $quantity): void
    {
        if ($quantity <= 0) {
            throw InvalidCartProductQuantityException::create($quantity);
        }

        $this->quantity += $quantity;
    }
}