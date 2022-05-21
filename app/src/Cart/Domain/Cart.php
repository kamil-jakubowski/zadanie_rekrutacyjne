<?php
declare(strict_types=1);

namespace App\Cart\Domain;

use App\Cart\Domain\Exception\CartMaximumProductsReachedException;
use App\Cart\Domain\Exception\NotCartProductException;
use App\Cart\Domain\Exception\ProductAlreadyExistsInCartException;
use App\Cart\Domain\Exception\ProductDoesNotExistsInCartException;
use App\Product\Domain\Product;
use App\Shared\Domain\Interfaces\Entity as EntityInterface;
use App\Shared\Domain\Traits\DateCreationTrait;
use App\Shared\Domain\Traits\Entity as EntityTrait;
use App\Shared\Domain\ValueObject\Uuid;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Class {Cart}
 * @author Kamil Jakubowski <kamil.jakubowski@gmail.com>
 */
class Cart implements EntityInterface
{
    // maximum products in the cart
    public const MAX_PRODUCTS_IN_CART = 3;

    // Entity identify
    use EntityTrait;
    // information of date created
    use DateCreationTrait;

    protected Collection $products;

    /**
     * Cart constructor
     * Domain object constructor should be private/protected and created only by static factories to encapsulate identity (id uuid) and metadata
     * Infrastructure child object can create another methods to override/set identity of already persisted entity in the infrastructure (like Doctrine ORM entity proxies do for example)
     *
     * @param ArrayCollection $products
     * @param int|null $id
     * @param Uuid|null $uuid
     * @param DateTimeImmutable|null $createdDate
     */
    protected function __construct(
        ArrayCollection $products = null,
        ?int $id = null,
        ?Uuid $uuid = null,
        ?DateTimeImmutable $createdDate = null
    )
    {
        $this->setupIdentity($id, $uuid);
        $this->setupCreatedDate($createdDate);

        $this->products = ($products !== null) ? $products : new ArrayCollection();
    }

    static public function createNew(array $products = []) : static
    {
        return new static(new ArrayCollection($products));
    }

    /**
     * Add product to the cart
     * If already exists it increases ProductCart::quantity
     *
     * @param Product $product
     * @return void
     */
    public function addProduct(Product $product): void
    {
        if ($this->hasProductInCart($product)) {
            throw ProductAlreadyExistsInCartException::create();
        }

        if ($this->hasMaximumProductsReached()) {
            throw CartMaximumProductsReachedException::create();
        }

        $this->products->add($product);
    }

    public function removeProduct(Product $product): void
    {
        if (!$this->hasProductInCart($product)) {
            throw ProductDoesNotExistsInCartException::create($product, $this);
        }

        $this->products->remove($this->products->indexOf($product));
    }

    /**
     * Get collection of Products
     * @return array - not returing collection to avoid external manipulation of collection which can affect unwanted collection of product changes
     */
    public function getProducts(): array
    {
        return $this->products->toArray();
    }

    public function hasProductInCart(Product $product): bool
    {
        return $this->products->contains($product);
    }

    public function getProduct(Product $product): Product
    {
        if (!$this->hasProductInCart($product)) {
            throw ProductDoesNotExistsInCartException::create($product, $this);
        }

       return $this->products->get($this->products->indexOf($product));
    }

    private function hasMaximumProductsReached(): bool
    {
        return $this->products->count() >= static::MAX_PRODUCTS_IN_CART;
    }
}