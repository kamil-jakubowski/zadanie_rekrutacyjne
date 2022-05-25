<?php
declare(strict_types=1);

namespace App\Cart\Application\Command\CreateCart;

use App\Shared\CQRS\Command\CommandInterface;
use App\Shared\CQRS\Command\CommandResourceCreatingInterface;

/**
 * @author Kamil Jakubowski <kamil.jakubowski@gmail.com>
 */
class CreateCartCommand implements CommandInterface, CommandResourceCreatingInterface
{
    private string $newCartUuid;

    public function getNewResourceUuid(): string
    {
        return $this->newCartUuid;
    }

    public function setNewResourceUuid(string $uuid): void
    {
        $this->newCartUuid = $uuid;
    }

}