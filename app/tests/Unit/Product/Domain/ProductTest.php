<?php
declare(strict_types=1);

namespace App\Tests\Unit\Product\Domain\ProductTest;

use App\Product\Domain\Exception\InvalidProductNameException;
use App\Product\Domain\Exception\InvalidProductPriceException;
use App\Product\Domain\Product;
use App\Shared\Domain\ValueObject\Uuid;
use App\Tests\Helper\ProductTestHelper;
use PHPUnit\Framework\TestCase;

/**
 * Class {ProductTest}
 * @author Kamil Jakubowski <kamil.jakubowski@gmail.com>
 */
class ProductTest extends TestCase
{
    use ProductTestHelper;

    public function testNewCreateProductSuccessWithNewIdentity(): void
    {
        // Given
        $name = $this->generateValidProductName();
        $price = $this->generateValidPrice();

        // When
        $product = Product::createNew($name, $price);

        // Then
        $this->assertEquals($name, $product->getName());
        $this->assertEquals($price, $product->getPrice());
        $this->assertEquals(null, $product->getId());
        $this->assertTrue($product->getUuid() instanceof Uuid);
        $this->assertNotNull($product->getDateCreated());
    }

    public function testCreatingProductWithTooLongNameShouldFail(): void
    {
        // Given
        $tooLongName = $this->generateTooLongProductName();
        $validPrice = $this->generateValidPrice();

        // Then
        $this->expectException(InvalidProductNameException::class);
        $this->expectExceptionCode(InvalidProductNameException::CODE_NAME_TOO_LONG);

        // When
        Product::createNew($tooLongName, $validPrice);
    }

    public function testCreatingProductWithTooShortNameShouldFail(): void
    {
        // Given
        $tooShortName = $this->generateTooShortProductName();
        $validPrice = $this->generateValidPrice();

        // Then
        $this->expectException(InvalidProductNameException::class);
        $this->expectExceptionCode(InvalidProductNameException::CODE_NAME_TOO_SHORT);

        // When
        Product::createNew($tooShortName, $validPrice);
    }

    public function testCreatingProductWithTooHighPriceShouldFailed(): void
    {
        // Given
        $validName = $this->generateValidProductName();
        $tooHighPrice = $this->generatePriceGreaterThanMax();

        // Then
        $this->expectException(InvalidProductPriceException::class);
        $this->expectExceptionCode(InvalidProductPriceException::CODE_PRICE_TOO_HIGH);

        // When
        Product::createNew($validName, $tooHighPrice);
    }

    public function testCreatingProductWithTooHighPricePrecisionShouldFailed(): void
    {
        // Given
        $validName = $this->generateValidProductName();
        $price = $this->generateValidPrice() + 0.001; // valid price but with third precision point

        // Then
        $this->expectException(InvalidProductPriceException::class);
        $this->expectExceptionCode(InvalidProductPriceException::CODE_PRICE_TOO_HIGH_PRECISION);

        // When
        Product::createNew($validName, $price);
    }

    public function testCreatingProductWithNegativePriceShouldFailed(): void
    {
        // Given
        $validName = $this->generateValidProductName();

        // Then
        $this->expectException(InvalidProductPriceException::class);
        $this->expectExceptionCode(InvalidProductPriceException::CODE_PRICE_NEGATIVE);

        // When
        Product::createNew($validName, -1);
    }

    public function testCreatingProductWithZeroPriceShouldFailed(): void
    {
        // Given
        $validName = $this->generateValidProductName();

        // Then
        $this->expectException(InvalidProductPriceException::class);
        $this->expectExceptionCode(InvalidProductPriceException::CODE_PRICE_ZERO);

        // When
        Product::createNew($validName, 0);
    }
}