<?php
declare(strict_types=1);

namespace App\Product\Application\Command\RemoveProduct;

use App\Shared\CQRS\Command\CommandHandlerInterface;

/**
 * @author Kamil Jakubowski <kamil.jakubowski@gmail.com>
 */
interface RemoveProductHandlerInterface extends CommandHandlerInterface
{
    public function __invoke(RemoveProductCommand $cmd): void;
}