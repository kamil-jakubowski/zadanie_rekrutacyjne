<?php
declare(strict_types=1);

namespace App\Cart\Domain;

use App\Product\Domain\Product;
use App\Shared\Domain\ValueObject\Uuid;

/**
 * Interface {CartRepositoryInterface}
 * @author Kamil Jakubowski <kamil.jakubowski@gmail.com>
 */
interface CartRepositoryInterface
{
    public function createNewCart(): void;

    public function updateCart(Cart $cart): void;

    public function getCart(Uuid $uuid): Cart;
}