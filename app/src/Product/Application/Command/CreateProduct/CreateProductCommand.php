<?php
declare(strict_types=1);

namespace App\Product\Application\Command\CreateProduct;

use App\Shared\CQRS\Command\CommandInterface;
use App\Shared\CQRS\Command\CommandResourceCreatingInterface;

/**
 * @author Kamil Jakubowski <kamil.jakubowski@gmail.com>
 */
class CreateProductCommand implements CommandInterface, CommandResourceCreatingInterface
{
    private string $name;

    private float $price;

    /**
     * @see CommandResourceCreatingInterface Doc explanation why that way ;)
     * @var string
     */
    private string $createdUuid;

    /**
     * @param string $name
     * @param float $price
     */
    public function __construct(string $name, float $price)
    {
        $this->name = $name;
        $this->price = $price;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @see CommandResourceCreatingInterface Doc explanation why that way ;)
     * @param string $uuid
     * @return void
     */
    public function setNewResourceUuid(string $uuid): void
    {
        $this->createdUuid = $uuid;
    }

    public function getNewResourceUuid(): string
    {
        return $this->createdUuid;
    }


}