<?php
declare(strict_types=1);

namespace App\Cart\Application\Query\Exception;

use App\Shared\CQRS\Query\QueryResourceNotFound;

/**
 * @author Kamil Jakubowski <kamil.jakubowski@gmail.com>
 */
class CartNotFoundException extends QueryResourceNotFound
{
    static public function create(string $uuid): static
    {
        return new self(sprintf('Cart with uuid %s does not exist', $uuid));
    }
}