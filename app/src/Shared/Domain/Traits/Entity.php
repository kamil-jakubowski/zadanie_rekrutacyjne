<?php
declare(strict_types=1);

namespace App\Shared\Domain\Traits;

use App\Shared\Domain\ValueObject\Uuid;

/**
 * Trait for Domain Entities - give identity to an entity class
 * Int ID for relations databases purposes - better indexing, faster relations
 * UUID for unique identification outside the database (API, another databases, another microservices, caching,etc)
 * @author Kamil Jakubowski <kamil.jakubowski@gmail.com>
 */
trait Entity
{
    /**
     * *As described above in class doc* and:
     * @see \App\Shared\Domain\Interfaces\Entity::getId()
     * @var int|null
     */
    protected ?int $id;

    /**
     * *As described above in class doc* and:
     * @see \App\Shared\Domain\Interfaces\Entity::getId()
     * @var Uuid
     */
    protected Uuid $uuid;

    /**
     * If identities are not null it save it on properties
     * If are null - sets id as null, and generate new uuid
     * Protected to encapsulate overriding identity of entity
     * @param int|null $id
     * @param Uuid|null $uuid
     * @return void
     */
    protected function setupIdentity(?int $id, ?Uuid $uuid) {
        $this->id = $id; // will be assigned in infrastructure by ID of given database, int ID is better for relational database relations and performance
        $this->uuid = $uuid ?: Uuid::random();  // generate a random uuid
    }

    /**
     * @inheritDoc
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @inheritDoc
     * @return Uuid
     */
    public function getUuid(): Uuid
    {
        return $this->uuid;
    }
}