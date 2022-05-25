<?php
declare(strict_types=1);

namespace App\Product\Application\Query\ProductList\ViewModel;

use App\Product\Domain\Product;

/**
 * Class {ProductViewModel}
 * @author Kamil Jakubowski <kamil.jakubowski@gmail.com>
 */
class ProductViewModel
{
    private string $uuid;
    private string $name;
    private float $price;

    private function __construct(
        string $uuid,
        string $name,
        float $price
    )
    {
        $this->uuid = $uuid;
        $this->price = $price;
        $this->name = $name;
    }

    static public function createFromDomainProduct(Product $product)
    {
        $uuid = (string) $product->getUuid();
        $price = $product->getPrice();
        $name = $product->getName();

        return new static($uuid, $name, $price);
    }

    /**
     * @return string
     */
    public function getUuid(): string
    {
        return $this->uuid;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }


}