<?php
declare(strict_types=1);

namespace App\Product\Application\Command\UpdateProductPrice;

use App\Product\Application\Command\Exception\ProductNotFoundException;
use App\Product\Domain\ProductRepositoryInterface;
use App\Shared\Domain\ValueObject\Uuid;

/**
 * @author Kamil Jakubowski <kamil.jakubowski@gmail.com>
 */
class UpdateProductPriceHandler implements UpdateProductPriceHandlerInterface
{
    private ProductRepositoryInterface $productRepository;

    /**
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function __invoke(UpdateProductPriceCommand $cmd): void
    {
        $uuid = $cmd->getUuid();
        $product = $this->productRepository->findProductByUuid(Uuid::fromString($uuid));

        if (!$product) {
            throw ProductNotFoundException::create($uuid);
        }

        $product->setPrice($cmd->getPrice());
        $this->productRepository->updateProduct($product);
    }


}