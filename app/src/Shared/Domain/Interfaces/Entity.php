<?php
declare(strict_types=1);

namespace App\Shared\Domain\Interfaces;

use App\Shared\Domain\ValueObject\Uuid;

/**
 * Interface for entites (which has identity from definition)
 * Int ID for relations databases purposes - better indexing, faster relations
 * UUID for unique identification outside the database (API, another databases, another microservices, caching,etc)
 * @author Kamil Jakubowski <kamil.jakubowski@gmail.com>
 * @see \App\Shared\Domain\Traits\Entity as implementation trait for that interface
 */
interface Entity
{
    /**
     * Purpose as described above
     * ID can be null when is not persistent to any database which generates ID
     * @return int|null
     */
    public function getId(): ?int;

    /**
     * Purpose as described above
     * UUID cannot be null because it is autogenerated when object is creating (not when is persisting to concrete infrastructure db)
     * @return mixed
     */
    public function getUuid();
}