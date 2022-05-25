<?php
declare(strict_types=1);

namespace App\Product\Application\Command\UpdateProductName;

use App\Product\Application\Command\Exception\ProductNotFoundException;
use App\Product\Application\Command\UpdateProductName\UpdateProductNameHandlerInterface;
use App\Product\Domain\ProductRepositoryInterface;
use App\Shared\Domain\ValueObject\Uuid;

/**
 * @author Kamil Jakubowski <kamil.jakubowski@gmail.com>
 */
class UpdateProductNameHandler implements UpdateProductNameHandlerInterface
{
    private ProductRepositoryInterface $productRepository;

    /**
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function __invoke(UpdateProductNameCommand $cmd): void
    {
        $uuid = $cmd->getUuid();
        $product = $this->productRepository->findProductByUuid(Uuid::fromString($uuid));

        if (!$product) {
            throw ProductNotFoundException::create($uuid);
        }

        $product->setName($cmd->getName());
        $this->productRepository->updateProduct($product);
    }


}