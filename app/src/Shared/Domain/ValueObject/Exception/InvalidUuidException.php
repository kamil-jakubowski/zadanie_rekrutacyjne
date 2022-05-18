<?php
declare(strict_types=1);

namespace App\Shared\Domain\ValueObject\Exception;

use App\Shared\Domain\InvalidDomainArgumentException;

/**
 * Class {InvalidUuidException}
 * @author Kamil Jakubowski <kamil.jakubowski@gmail.com>
 */
class InvalidUuidException extends InvalidDomainArgumentException
{
    static public function notUuidString(string $stringUuid): static
    {
        return new static(sprintf("Uuid string must be valid UUID string. Given string: %s", $stringUuid));
    }
}