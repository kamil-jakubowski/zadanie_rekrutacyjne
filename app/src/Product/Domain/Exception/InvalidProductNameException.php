<?php
declare(strict_types=1);

namespace App\Product\Domain\Exception;

use App\Product\Domain\Product;
use App\Shared\Domain\InvalidDomainArgumentException;

/**
 * Class {InvalidProductNameException}
 * @author Kamil Jakubowski <kamil.jakubowski@gmail.com>
 */
class InvalidProductNameException extends InvalidDomainArgumentException
{
    public const CODE_NAME_TOO_LONG = 1;
    public const CODE_NAME_TOO_SHORT = 2;

    static public function create(string $name, int $code): static
    {
        $reason = match($code) {
            static::CODE_NAME_TOO_LONG => static::getMessageTooLong($name),
            static::CODE_NAME_TOO_SHORT => static::getMessageTooShort($name),
            default => "Unknown"
        };

        $msg = sprintf(
            "Product name '%s' is invalid: %s",
            $name,
            $reason
        );

        return new static($msg, $code);
    }

    static private function getMessageTooLong(string $name): string
    {
        return sprintf(
            "Maximum length of product name is %d. Given name is: '%s' length: %d",
            Product::MAX_NAME_LENGTH,
            $name,
            strlen($name)
        );
    }

    static private function getMessageTooShort(string $name): string
    {
        return sprintf(
            "Minimum length of product name is %d. Given name is: '%s' length: %d",
            Product::MIN_NAME_LENGTH,
            $name,
            strlen($name)
        );
    }
}