<?php
declare(strict_types=1);

namespace App\Shared\Domain\Interfaces;

use DateTimeImmutable;

/**
 * @author Kamil Jakubowski <kamil.jakubowski@gmail.com>
 */
interface DateCreationInterface
{

    /**
     * @return DateTimeImmutable
     */ 
    public function getDateCreated(): ?DateTimeImmutable;

    /**
     * @param DateTimeImmutable $dateCreated
     */
    public function setDateCreated(DateTimeImmutable $dateCreated);
    
}