<?php
declare(strict_types=1);

namespace App\Product\Application\Command\UpdateProductPrice;

use App\Shared\CQRS\Command\CommandInterface;

/**
 * @author Kamil Jakubowski <kamil.jakubowski@gmail.com>
 */
class UpdateProductPriceCommand implements CommandInterface
{
    private string $uuid;

    private float $price;

    /**
     * @param string $uuid
     * @param float $price
     */
    public function __construct(string $uuid, float $price)
    {
        $this->uuid = $uuid;
        $this->price = $price;
    }

    /**
     * @return string
     */
    public function getUuid(): string
    {
        return $this->uuid;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }


}