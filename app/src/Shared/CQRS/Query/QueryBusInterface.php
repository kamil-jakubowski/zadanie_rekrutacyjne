<?php
declare(strict_types=1);

namespace App\Shared\CQRS\Query;

/**
 * Interface {QueryBusInterface}
 * @author Kamil Jakubowski <kamil.jakubowski@gmail.com>
 */
interface QueryBusInterface
{
    public function dispatch(QueryInterface $query);
}