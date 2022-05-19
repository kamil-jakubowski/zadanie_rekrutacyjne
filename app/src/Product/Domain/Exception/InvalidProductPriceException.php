<?php
declare(strict_types=1);

namespace App\Product\Domain\Exception;

use App\Shared\Exception\InvalidDomainArgumentException;

/**
 * An exception to th
 * @author Kamil Jakubowski <kamil.jakubowski@gmail.com>
 */
class InvalidProductPriceException extends InvalidDomainArgumentException
{
    public const CODE_PRICE_NEGATIVE = 1;
    public const CODE_PRICE_ZERO = 2;
    public const CODE_PRICE_TOO_HIGH = 3;
    public const CODE_PRICE_TOO_HIGH_PRECISION = 4;

    static public function create(float $price, int $code): static
    {
        $reason = match($code) {
            static::CODE_PRICE_NEGATIVE => static::getMessageNegative($price),
            static::CODE_PRICE_ZERO => static::getMessageZero($price),
            static::CODE_PRICE_TOO_HIGH => static::getMessageTooHigh($price),
            static::CODE_PRICE_TOO_HIGH_PRECISION => static::getMessageTooHighPrecision($price),
            default => "Unknown"
        };

        $msg = sprintf(
            "Product price '%d' is invalid: %s",
            $price,
            $reason
        );

        return new static($msg, $code);
    }

    static private function getMessageZero(float $price): string
    {
        return sprintf(
            "The price is zero. given %d",
            $price
        );
    }

    static private function getMessageNegative(float $price): string
    {
        return sprintf(
            "The price must be positive number. given %d",
            $price
        );
    }
    static private function getMessageTooHigh(float $price): string
    {
        return sprintf(
            "The price is zero. given %d",
            $price
        );
    }

    static private function getMessageTooHighPrecision(float $price): string
    {
        return sprintf(
            "The price must be float with max 2 points precision",
            $price
        );
    }
}