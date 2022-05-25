<?php
declare(strict_types=1);

namespace App\Product\Application\Query\ProductList\ViewModel;

use App\Product\Domain\Product;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @author Kamil Jakubowski <kamil.jakubowski@gmail.com>
 */
class ProductViewModelList extends ArrayCollection
{
    /**
     * Have an information about all available products in database
     */
    protected int $quantityOfAll;

    /**
     * @return int
     */
    public function getQuantityOfAll(): int
    {
        return $this->quantityOfAll;
    }

    /**
     * @param int $quantityOfAll
     */
    public function setQuantityOfAll(int $quantityOfAll): void
    {
        $this->quantityOfAll = $quantityOfAll;
    }

    static public function createFromDomainProducts(array $products, int $quantityOfAll): static
    {
        // I wish to have Generics in PHP :(
        $validateFn = function($product) {
            if (!$product instanceof Product) {
                throw new \InvalidArgumentException("Item is not valid domain Product");
            }
        };

        array_walk($products, $validateFn);

        $list = array_map(fn ($product) => ProductViewModel::createFromDomainProduct($product), $products);

        $collection = new static($list);
        $collection->setQuantityOfAll($quantityOfAll);
        return $collection;
    }
}