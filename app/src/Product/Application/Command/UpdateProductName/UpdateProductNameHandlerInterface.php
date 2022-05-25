<?php
declare(strict_types=1);

namespace App\Product\Application\Command\UpdateProductName;

use App\Product\Application\Command\UpdateProductName\UpdateProductNameCommand;
use App\Shared\CQRS\Command\CommandHandlerInterface;

/**
 * @author Kamil Jakubowski <kamil.jakubowski@gmail.com>
 */
interface UpdateProductNameHandlerInterface extends CommandHandlerInterface
{
    public function __invoke(UpdateProductNameCommand $cmd): void;
}