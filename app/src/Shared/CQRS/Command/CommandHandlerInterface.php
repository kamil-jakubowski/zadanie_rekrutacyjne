<?php
declare(strict_types=1);

namespace App\Shared\CQRS\Command;

use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

/**
 * Interface {CommandHandlerInterface}
 * @author Kamil Jakubowski <kamil.jakubowski@gmail.com>
 */
interface CommandHandlerInterface extends MessageHandlerInterface
{

}