<?php
declare(strict_types=1);

namespace App\Product\Application\Query\Product;

use App\Product\Application\Query\ProductList\ViewModel\ProductViewModel;
use App\Shared\CQRS\Query\QueryHandlerInterface;

/**
 * @author Kamil Jakubowski <kamil.jakubowski@gmail.com>
 */
interface ProductHandlerInterface extends QueryHandlerInterface
{
    public function __invoke(ProductQuery $query): ?ProductViewModel;
}