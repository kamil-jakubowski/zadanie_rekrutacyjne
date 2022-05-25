<?php
declare(strict_types=1);

namespace App\Product\Domain;

use App\Shared\Domain\ValueObject\Uuid;
use Doctrine\Common\Collections\ArrayCollection;

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

    /**
     * Find product by given id, return product if exists, return null if not
     * @param int $id
     * @return Product|null
     */
    public function findProductById(int $id): ?Product;

    /**
     * Find product by given name, return product if exists, return null if not
     * @param string $name
     * @return Product|null
     */
    public function findProductByName(string $name): ?Product;

    public function getProductsList(int $offset, int $limit): array;

    public function getAllProductsQuantity(): int;
}