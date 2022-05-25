<?php
declare(strict_types=1);

namespace App\Product\Application\Query\ProductList\Exception;

use App\Product\Application\Query\ProductList\ProductListQuery;
use App\Shared\CQRS\Query\InvalidQueryArgumentException;

/**
 * Class {MaxProductPerPageExceedeException}
 * @author Kamil Jakubowski <kamil.jakubowski@gmail.com>
 */
class MaxProductPerPageExceededException extends InvalidQueryArgumentException
{
    static public function create(int $given): static
    {
        return new static(sprintf("itemsPerPage is max: %d, given: %d", ProductListQuery::MAX_PER_PAGE, $given));
    }
}