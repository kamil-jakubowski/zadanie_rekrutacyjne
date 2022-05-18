<?php
declare(strict_types=1);

namespace App\Product\Domain;

use App\Domain\Shared\ValueObject\Uuid;

/**
 * Interface {ProductRepositoryInterface}
 * @author Kamil Jakubowski <kamil.jakubowski@gmail.com>
 */
interface ProductRepositoryInterface
{
    public function addProduct(Product $product): void;
    public function removeProduct(Product $product): void;
    public function updateProduct(Product $product): void;

    /**
     * Find product by given uuid, return product if exists, return null if not
     * @param Uuid $uuid
     * @return Product|null
     */
    public function findProductByUuid(Uuid $uuid): ?Product;

    public function getProductsList(); // @todo to extend
}