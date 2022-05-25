<?php
declare(strict_types=1);

namespace App\Product\Application\Query\Product;

use App\Shared\CQRS\Query\QueryInterface;

/**
 * @author Kamil Jakubowski <kamil.jakubowski@gmail.com>
 */
class ProductQuery implements QueryInterface
{
    private string $uuid;

    /**
     * @param string $uuid
     */
    public function __construct(string $uuid)
    {
        $this->uuid = $uuid;
    }

    /**
     * @return string
     */
    public function getUuid(): string
    {
        return $this->uuid;
    }


}