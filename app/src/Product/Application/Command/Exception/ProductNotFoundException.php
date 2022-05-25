<?php
declare(strict_types=1);

namespace App\Product\Application\Command\Exception;

use App\Shared\CQRS\Query\QueryResourceNotFound;

/**
 * @author Kamil Jakubowski <kamil.jakubowski@gmail.com>
 */
class ProductNotFoundException extends QueryResourceNotFound
{
    static public function create(string $uuid): static
    {
        return new self(sprintf('Product with uuid %s does not exist', $uuid));
    }
}