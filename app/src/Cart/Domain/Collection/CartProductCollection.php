<?php
declare(strict_types=1);

namespace App\Cart\Domain\Collection;

use App\Cart\Domain\CartProduct;
use App\Cart\Domain\Exception\CartProductNotFoundInCollectionException;
use App\Product\Domain\Product;
use App\Shared\Domain\EntityCollection;
use Doctrine\Common\Collections\Criteria;

/**
 * Collection for Cart Products
 * @author Kamil Jakubowski <kamil.jakubowski@gmail.com>
 */
class CartProductCollection extends EntityCollection
{
    /**
     * @param Product $product
     * @return CartProduct
     * @throws CartProductNotFoundInCollectionException when product is not found in the collection
     */
    public function getByProduct(Product $product): CartProduct
    {
        $criteria = Criteria::create()
            ->where(Criteria::expr()->eq('productUuidString', $product->getUuid()->toString()))
            ->setMaxResults(1);

        $product = $this->matching($criteria)->first();

        if (!$product) {
            throw CartProductNotFoundInCollectionException::create($product);
        }

        return $product;
    }
}