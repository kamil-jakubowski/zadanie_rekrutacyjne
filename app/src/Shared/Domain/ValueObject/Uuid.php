<?php
declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

use App\Shared\Domain\InvalidDomainArgumentException;
use App\Shared\Domain\ValueObject\Exception\InvalidUuidException;
use Ramsey\Uuid\Lazy\LazyUuidFromString;
use Ramsey\Uuid\Rfc4122\UuidV1;

/**
 * Class {Uuid}
 * @author Kamil Jakubowski <kamil.jakubowski@gmail.com>
 */
class Uuid
{
    /**
     * Uuidv1 chosen because it has timestamp included which helps with database index sorting
     * @var UuidV1 Uu
     */
    private UuidV1 $value;

    private function __construct(UuidV1 $value)
    {
        $this->value = $value;
    }

    static public function fromString(string $value): self
    {
        if (! \Ramsey\Uuid\Uuid::isValid($value)) {
            throw InvalidUuidException::notUuidString($value);
        }

        $uuid = \Ramsey\Uuid\Uuid::fromString($value);

        if ($uuid instanceof LazyUuidFromString) {
            try {
                $uuid = $uuid->toUuidV1();
            }
            catch (\Exception $e) {
                InvalidUuidException::notUuidString($value);
            }
        }

        return new self($uuid);
    }

    static public function random(): self
    {
        $uuid = \Ramsey\Uuid\Uuid::uuid1();

        if ($uuid instanceof LazyUuidFromString) {
            $uuid = $uuid->toUuidV1();
        }

        return new self($uuid);
    }

    public function toString(): string
    {
        return $this->value->toString();
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}