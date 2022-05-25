<?php
declare(strict_types=1);

namespace App\Product\Application\Query\ProductList\Exception;

use App\Product\Application\Query\ProductList\ProductListQuery;
use App\Shared\CQRS\Query\InvalidQueryArgumentException;

/**
 * Class {MaxProductPerPageExceedeException}
 * @author Kamil Jakubowski <kamil.jakubowski@gmail.com>
 */
class WrongPageNumberException extends InvalidQueryArgumentException
{
    static public function create(): static
    {
        return new static("Page number must be greater that 1");
    }
}