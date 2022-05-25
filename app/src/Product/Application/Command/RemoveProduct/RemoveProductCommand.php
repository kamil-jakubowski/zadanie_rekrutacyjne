<?php
declare(strict_types=1);

namespace App\Product\Application\Command\RemoveProduct;

use App\Shared\CQRS\Command\CommandInterface;

/**
 * @author Kamil Jakubowski <kamil.jakubowski@gmail.com>
 */
class RemoveProductCommand implements CommandInterface
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