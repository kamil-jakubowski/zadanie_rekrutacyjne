<?php
declare(strict_types=1);

namespace App\Cart\Domain\Exception;

use App\Shared\Exception\DomainInvalidException;

/**
 * Class {ProductAlreadyExistsInCartException}
 * @author Kamil Jakubowski <kamil.jakubowski@gmail.com>
 */
class ProductAlreadyExistsInCartException extends DomainInvalidException
{
    static public function create(): static
    {
        return new static(sprintf(
            "Product already exists in the cart"
        ));
    }
}