<?php
declare(strict_types=1);

namespace App\Product\Application\Command\CreateProduct;

use App\Product\Domain\Product;
use App\Product\Domain\ProductRepositoryInterface;

/**
 * Class {CreateProductHandler}
 * @author Kamil Jakubowski <kamil.jakubowski@gmail.com>
 */
class CreateProductHandler implements CreateProductHandlerInterface
{
    private ProductRepositoryInterface $productRepository;

    /**
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function __invoke(CreateProductCommand $cmd): void
    {
        $product = Product::createNew($cmd->getName(), $cmd->getPrice());
        $this->productRepository->addProduct($product);
        $cmd->setNewResourceUuid($product->getUuid()->toString());
    }
}