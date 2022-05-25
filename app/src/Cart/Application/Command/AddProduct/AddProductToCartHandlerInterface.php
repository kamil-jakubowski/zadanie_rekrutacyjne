<?php
declare(strict_types=1);

namespace App\Cart\Application\Command\AddProduct;

use App\Shared\CQRS\Command\CommandHandlerInterface;

/**
 * @author Kamil Jakubowski <kamil.jakubowski@gmail.com>
 */
interface AddProductToCartHandlerInterface extends CommandHandlerInterface
{
    public function __invoke(AddProductToCartCommand $cmd): void;
}