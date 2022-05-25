<?php
declare(strict_types=1);

namespace App\Cart\Domain\Exception;

use App\Shared\Exception\InvalidDomainArgumentException;

/**
 * @todo to delete
 * @deprecated IMPORTANT see CartProduct class explanation
 * @author Kamil Jakubowski <kamil.jakubowski@gmail.com>
 */
class InvalidCartProductQuantityException extends InvalidDomainArgumentException
{
    static public function create(int $quantity): static
    {
        $msg = sprintf("Wrong Cart Product quantity, given %d: %s", $quantity, "Quantity must be greater than 0");
        return new static($msg);
    }
}