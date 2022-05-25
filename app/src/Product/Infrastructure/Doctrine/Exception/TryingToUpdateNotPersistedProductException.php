<?php
declare(strict_types=1);

namespace App\Product\Infrastructure\Doctrine\Exception;

/**
 * Class {TryingToUpdateNotPersistedProductException}
 * @author Kamil Jakubowski <kamil.jakubowski@gmail.com>
 */
class TryingToUpdateNotPersistedProductException extends ProductRepositoryException
{

}