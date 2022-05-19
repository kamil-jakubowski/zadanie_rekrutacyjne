<?php
declare(strict_types=1);

namespace App\Cart\Domain\Exception;

use App\Cart\Domain\Cart;
use App\Product\Domain\Product;
use App\Shared\Exception\InvalidDomainArgumentException;

/**
 * Exception when trying to get product which is not in the cart
 * @author Kamil Jakubowski <kamil.jakubowski@gmail.com>
 */
class ProductDoesNotExistsInCartException extends InvalidDomainArgumentException
{
    static public function create(Product $product, Cart $cart): static
    {
        return new self(
            sprintf(
                "Product '%s' does not exists in Cart uuid '%s'",
                $product->getName(),
                (string) $cart->getUuid()
            )
        );
    }
}