<?php
declare(strict_types=1);

namespace App\Cart\Application\Query\GetCart;

use App\Cart\Application\Query\ViewModel\CartViewModel;
use App\Shared\CQRS\Query\QueryHandlerInterface;

/**
 * @author Kamil Jakubowski <kamil.jakubowski@gmail.com>
 */
interface GetCartHandlerInterface extends QueryHandlerInterface
{
    public function __invoke(GetCartQuery $query): CartViewModel;
}