<?php
declare(strict_types=1);

namespace App\Cart\Application\Query\ViewModel;

use App\Product\Application\Query\ProductList\ViewModel\ProductViewModel;

/**
 * @author Kamil Jakubowski <kamil.jakubowski@gmail.com>
 */
class CartViewModel
{
    private string $uuid;

    /**
     * @var array of ProductViewModel
     */
    private array $products;

    public function __construct(string $uuid)
    {
        $this->uuid = $uuid;
        $this->products = [];
    }

    /**
     * Still: really want to have Generics in PHP to append them all without validation Collection<ProductViewModel> like in Java for example
     * @param ProductViewModel $vmProduct
     * @return void
     */
    public function appendProductViewModel(ProductViewModel $vmProduct): void
    {
        $this->products[] = $vmProduct;
    }

    /**
     * @return array Array of ProductViewModel
     */
    public function getProducts(): array
    {
        return $this->products;
    }

    /**
     * @return string
     */
    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getTotalPrice(): float
    {
        $value = 0.0;
        foreach ($this->getProducts() as $product) {
            $value += $product->getPrice();
        }

        return round($value, 2);
    }
}