<?php
declare(strict_types=1);

namespace App\Product\Application\Query\ProductList;

use App\Product\Application\Query\ProductList\ViewModel\ProductViewModelList;
use App\Shared\CQRS\Query\QueryHandlerInterface;

/**
 * @author Kamil Jakubowski <kamil.jakubowski@gmail.com>
 */
interface ProductListHandlerInterface extends QueryHandlerInterface
{
    public function __invoke(ProductListQuery $query): ProductViewModelList;
}