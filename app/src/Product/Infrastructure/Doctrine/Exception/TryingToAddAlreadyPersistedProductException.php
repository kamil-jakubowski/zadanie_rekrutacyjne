<?php
declare(strict_types=1);

namespace App\Product\Infrastructure\Doctrine\Exception;

/**
 * Class {TryingToAddAlreadyPersistedCartException}
 * @author Kamil Jakubowski <kamil.jakubowski@gmail.com>
 */
class TryingToAddAlreadyPersistedProductException extends ProductRepositoryException
{

}