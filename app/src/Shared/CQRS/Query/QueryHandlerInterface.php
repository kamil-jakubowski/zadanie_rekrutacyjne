<?php
declare(strict_types=1);

namespace App\Shared\CQRS\Query;

use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

/**
 * Interface {QueryHandlerInterface}
 * @author Kamil Jakubowski <kamil.jakubowski@gmail.com>
 */
interface QueryHandlerInterface extends MessageHandlerInterface
{

}