<?php
declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

use App\Shared\Domain\ValueObject\Exception\InvalidUuidException;
use Ramsey\Uuid\Lazy\LazyUuidFromString;
use Ramsey\Uuid\Rfc4122\UuidV1;
use Ramsey\Uuid\UuidInterface;

/**
 * Class {Uuid}
 * @author Kamil Jakubowski <kamil.jakubowski@gmail.com>
 */
class Uuid
{
    /**
     * @var UuidInterface Uu
     */
    private UuidInterface $value;

    private function __construct(UuidInterface $value)
    {
        $this->value = $value;
    }

    static public function fromString(string $value): self
    {
        if (! \Ramsey\Uuid\Uuid::isValid($value)) {
            throw InvalidUuidException::notUuidString($value);
        }

        $uuid = \Ramsey\Uuid\Uuid::fromString($value);

        return new self($uuid);
    }

    static public function random(): self
    {
        $uuid = \Ramsey\Uuid\Uuid::uuid1();

        // Uuidv1 chosen because it has timestamp included which helps with database index sorting
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