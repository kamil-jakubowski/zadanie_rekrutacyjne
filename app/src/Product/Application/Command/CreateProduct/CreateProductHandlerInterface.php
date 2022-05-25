<?php
declare(strict_types=1);

namespace App\Product\Application\Command\CreateProduct;

use App\Shared\CQRS\Command\CommandHandlerInterface;

/**
 * Interface {CreateProductHandlerInterface}
 * @author Kamil Jakubowski <kamil.jakubowski@gmail.com>
 */
interface CreateProductHandlerInterface extends CommandHandlerInterface
{
    public function __invoke(CreateProductCommand $cmd): void;
}