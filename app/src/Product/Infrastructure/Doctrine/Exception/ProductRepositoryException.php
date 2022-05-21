<?php
declare(strict_types=1);

namespace App\Product\Infrastructure\Doctrine\Exception;

use Exception;

/**
 * Class {ProductRepositoryException}
 * @author Kamil Jakubowski <kamil.jakubowski@gmail.com>
 */
class ProductRepositoryException extends Exception
{
    static public function create($msg, \Throwable $previousException)
    {
        return new static($msg, 0, $previousException);
    }
}