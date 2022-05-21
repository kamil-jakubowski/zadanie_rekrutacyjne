<?php
declare(strict_types=1);

namespace App\Cart\Infrastructure\Doctrine\Exception;

use App\Product\Infrastructure\Doctrine\Exception\ProductRepositoryException;/**
 * @author Kamil Jakubowski <kamil.jakubowski@gmail.com>
 */
class TryingToAddAlreadyPersistedCartException extends ProductRepositoryException
{

}