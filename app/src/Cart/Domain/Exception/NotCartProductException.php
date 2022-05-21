<?php
declare(strict_types=1);

namespace App\Cart\Domain\Exception;

use App\Shared\Exception\InvalidDomainArgumentException;

/**
 * Exception when invalid argument that is not CartProduct served
 * @author Kamil Jakubowski <kamil.jakubowski@gmail.com>
 */
class NotCartProductException extends InvalidDomainArgumentException
{
    static public function create(): static
    {
        return new self("Product passed to Cart can be only CartProduct");
    }
}