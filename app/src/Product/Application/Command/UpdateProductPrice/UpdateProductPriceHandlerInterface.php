<?php
declare(strict_types=1);

namespace App\Product\Application\Command\UpdateProductPrice;

use App\Product\Application\Command\UpdateProductName\UpdateProductNameCommand;
use App\Product\Application\Command\UpdateProductPrice\UpdateProductPriceCommand;
use App\Shared\CQRS\Command\CommandHandlerInterface;

/**
 * @author Kamil Jakubowski <kamil.jakubowski@gmail.com>
 */
interface UpdateProductPriceHandlerInterface extends CommandHandlerInterface
{
    public function __invoke(UpdateProductPriceCommand $cmd): void;
}