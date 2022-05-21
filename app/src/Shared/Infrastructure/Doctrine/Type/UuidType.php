<?php
declare(strict_types=1);

namespace App\Shared\Infrastructure\Doctrine\Type;

use App\Shared\Domain\ValueObject\Exception\InvalidUuidException;
use App\Shared\Domain\ValueObject\Uuid;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\GuidType;
use Exception;
use InvalidArgumentException;

/**
 * Doctrine mapping type for App\Shared\Domain\ValueObject\Uuid valueObject
 * @author Kamil Jakubowski <kamil.jakubowski@gmail.com>
 */
class UuidType extends GuidType
{
    const NAME = 'vo_uuid';

    /**
     * {@inheritdoc}
     * @return ?string
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return $value;
        }

        if (is_string($value)) {
            if (! \Ramsey\Uuid\Uuid::isValid($value)) {
                throw InvalidUuidException::notUuidString($value);
            }

            return $value;
        }

        if ($value instanceof Uuid) {
            return $value->toString();
        }

        throw ConversionException::conversionFailedInvalidType($value, $this->getName(), ['null', Uuid::class]);
    }

    /**
     * {@inheritdoc}
     * @return ?Uuid
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value === null || $value instanceof Uuid) {
            return $value;
        }

        try {
            $val = (string) $value;
        } catch (Exception $e) {
            throw ConversionException::conversionFailedInvalidType($value, $this->getName(), [null, Uuid::class, 'stringable']);
        }

        try {
            $uuid = Uuid::fromString($val);
        } catch (InvalidArgumentException $e) {
            throw ConversionException::conversionFailed($value, $this->getName(), $e);
        }

        return $uuid;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return self::NAME;
    }
}