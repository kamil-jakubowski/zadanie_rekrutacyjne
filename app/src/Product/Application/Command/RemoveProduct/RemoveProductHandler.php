<?php
declare(strict_types=1);

namespace App\Product\Application\Command\RemoveProduct;

use App\Product\Application\Command\Exception\ProductNotFoundException;
use App\Product\Domain\ProductRepositoryInterface;
use App\Shared\Domain\ValueObject\Uuid;

/**
 * @author Kamil Jakubowski <kamil.jakubowski@gmail.com>
 */
class RemoveProductHandler implements RemoveProductHandlerInterface
{
    private ProductRepositoryInterface $productRepository;

    /**
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function __invoke(RemoveProductCommand $cmd): void
    {
        $uuid = $cmd->getUuid();
        $product = $this->productRepository->findProductByUuid(Uuid::fromString($uuid));

        if (!$product) {
            throw ProductNotFoundException::create($uuid);
        }

        $this->productRepository->removeProduct($product);
    }
}