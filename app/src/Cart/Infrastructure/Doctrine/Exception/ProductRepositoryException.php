<?php
declare(strict_types=1);

namespace App\Cart\Infrastructure\Doctrine\Exception;

use Exception;

/**
 * @author Kamil Jakubowski <kamil.jakubowski@gmail.com>
 */
class CartRepositoryException extends Exception
{
    static public function create($msg, \Throwable $previousException)
    {
        return new static($msg, 0, $previousException);
    }
}