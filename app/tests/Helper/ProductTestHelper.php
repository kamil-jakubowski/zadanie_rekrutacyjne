<?php
declare(strict_types=1);

namespace App\Tests\Helper;

use App\Product\Domain\Product;

/**
 * Trait {ProductTestHelper}
 * @author Kamil Jakubowski <kamil.jakubowski@gmail.com>
 */
trait ProductTestHelper
{
    private function generateValidPrice(): float
    {
        return round(Product::MAX_PRODUCT_PRICE - 0.1, 2);
    }

    private function generateString(int $length): string
    {
        $characters = range('A', 'z');
        $name = '';
        for ($i=0; $i<$length ; $i++) {
            $char = $characters[rand(0,count($characters)-1)];
            $name .= $char;
        }

        return $name;
    }

    private function generateTooLongProductName(): string
    {
        return $this->generateString(Product::MAX_NAME_LENGTH+1);
    }

    private function generateTooShortProductName(): string
    {
        return $this->generateString(Product::MIN_NAME_LENGTH-1);
    }

    private function generateValidProductName(): string
    {
        $min = (Product::MIN_NAME_LENGTH < 0) ? 0 : Product::MIN_NAME_LENGTH;
        return $this->generateString($min);
    }

    private function generatePriceGreaterThanMax(): float
    {
        return round(Product::MAX_PRODUCT_PRICE + 1, 2);
    }

    /**
     * Generates new valid product with random valid data
     * @return Product
     */
    private function getNewProduct(): Product
    {
        return Product::createNew($this->generateValidProductName(), $this->generateValidPrice());
    }
}