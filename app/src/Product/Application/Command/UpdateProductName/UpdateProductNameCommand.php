<?php
declare(strict_types=1);

namespace App\Product\Application\Command\UpdateProductName;

use App\Shared\CQRS\Command\CommandInterface;

/**
 * @author Kamil Jakubowski <kamil.jakubowski@gmail.com>
 */
class UpdateProductNameCommand implements CommandInterface
{
    private string $uuid;

    private string $name;

    /**
     * @param string $uuid
     * @param string $name
     */
    public function __construct(string $uuid, string $name)
    {
        $this->uuid = $uuid;
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getUuid(): string
    {
        return $this->uuid;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}