<?php
declare(strict_types=1);

namespace App\Shared\CQRS\Command;

/**
 * Interface {CommandBusInterface}
 * @author Kamil Jakubowski <kamil.jakubowski@gmail.com>
 */
interface CommandBusInterface
{
    public function dispatch(CommandInterface $command): void;
}