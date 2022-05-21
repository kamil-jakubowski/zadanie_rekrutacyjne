<?php
declare(strict_types=1);

namespace App\Cart\Domain;

use App\Product\Domain\Product;
use App\Cart\Domain\Exception\InvalidCartProductQuantityException;
use App\Shared\Domain\ValueObject\Uuid;

/**
 * Class represent product and its quantity in the cart
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