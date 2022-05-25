<?php
declare(strict_types=1);

namespace App\Cart\Application\Command\CreateCart;

use App\Shared\CQRS\Command\CommandHandlerInterface;

/**
 * @author Kamil Jakubowski <kamil.jakubowski@gmail.com>
 */
interface CreateCartHandlerInterface extends CommandHandlerInterface
{
    public function __invoke(CreateCartCommand $cmd): void;
}