<?php
declare(strict_types=1);

namespace App\Shared\Domain\Traits;

use DateTimeImmutable;

/**
 * Trait for add dateCreated to entities
 * @author Kamil Jakubowski <kamil.jakubowski@gmail.com>
 */
trait DateCreationTrait
{
    /**
     * @var DateTimeImmutable
     */
    private ?DateTimeImmutable $dateCreated = null;
    //private ?User $createdBy = null;

    /**
     * Sets given Date or of it is null create new object with NOW datetime (new entity)
     * @param DateTimeImmutable|null $createdDate
     * @return void
     */
    protected function setupCreatedDate(?DateTimeImmutable $createdDate = null): void
    {
        $createdDate = $createdDate ?: new DateTimeImmutable();
        $this->setDateCreated($createdDate);
    }

    /**
     * @return DateTimeImmutable
     */ 
    public function getDateCreated(): ?DateTimeImmutable
    {
        return $this->dateCreated;
    }

    /**
     * @param DateTimeImmutable $dateCreated
     */
    public function setDateCreated(DateTimeImmutable $dateCreated): void
    {
        $this->dateCreated = $dateCreated;
    }
    
}