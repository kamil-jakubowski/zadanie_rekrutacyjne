<?php
declare(strict_types=1);

namespace App\Cart\Application\Command\AddProduct;

use App\Shared\CQRS\Command\CommandInterface;

/**
 * @author Kamil Jakubowski <kamil.jakubowski@gmail.com>
 */
class AddProductToCartCommand implements CommandInterface
{
    private string $cartUuid;

    private string $productUuid;

    /**
     * @param string $cartUuid
     * @param string $productUuid
     */
    public function __construct(string $cartUuid, string $productUuid)
    {
        $this->cartUuid = $cartUuid;
        $this->productUuid = $productUuid;
    }

    /**
     * @return string
     */
    public function getCartUuid(): string
    {
        return $this->cartUuid;
    }

    /**
     * @return string
     */
    public function getProductUuid(): string
    {
        return $this->productUuid;
    }


}