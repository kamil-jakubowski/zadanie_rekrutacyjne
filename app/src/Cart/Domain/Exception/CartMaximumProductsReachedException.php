<?php
declare(strict_types=1);

namespace App\Cart\Domain\Exception;

use App\Cart\Domain\Cart;
use App\Shared\Exception\DomainInvalidException;

/**
 * Exception for case when Cart is fulfilled and another product cannot be added
 * @author Kamil Jakubowski <kamil.jakubowski@gmail.com>
 */
class CartMaximumProductsReachedException extends DomainInvalidException
{
    static public function create(): static
    {
        return new static(sprintf(
           "Cart is full. The maximum number of products is %d. You cannot add another product",
            Cart::MAX_PRODUCTS_IN_CART
        ));
    }
}