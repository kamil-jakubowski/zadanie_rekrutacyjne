<?php
declare(strict_types=1);

namespace App\Product\Application\Query\ProductList;

use App\Product\Application\Query\ProductList\ViewModel\ProductViewModelList;
use App\Product\Domain\ProductRepositoryInterface;

/**
 * @author Kamil Jakubowski <kamil.jakubowski@gmail.com>
 */
class ProductListHandler implements ProductListHandlerInterface
{
    private ProductRepositoryInterface $productRepository;

    /**
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }


    public function __invoke(ProductListQuery $query): ProductViewModelList
    {
        $offset = ($query->getItemsPerPage())*($query->getPage()-1);
        $products = $this->productRepository->getProductsList($offset, $query->getItemsPerPage());
        $quantityOfAll =$this->productRepository->getAllProductsQuantity();

        return ProductViewModelList::createFromDomainProducts($products, $quantityOfAll);
    }

}