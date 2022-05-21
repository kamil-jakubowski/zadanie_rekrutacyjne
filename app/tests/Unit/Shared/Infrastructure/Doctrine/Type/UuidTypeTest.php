<?php

namespace App\Tests\Unit\Shared\Infrastructure\Doctrine\Type;

use App\Shared\Domain\ValueObject\Exception\InvalidUuidException;
use App\Shared\Infrastructure\Doctrine\Type\UuidType;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use PHPUnit\Framework\TestCase;
use App\Shared\Domain\ValueObject\Uuid;

class UuidTypeTest extends TestCase
{
    private UuidType $uuidType;

    private AbstractPlatform $platform;

    private string $validUuid;

    protected function setUp(): void
    {
        $this->uuidType = new UuidType();
        $this->platform = $this->getMockForAbstractClass(AbstractPlatform::class);
        $this->validUuid = "029232f6-da88-11ec-9d64-0242ac120002";
    }

    public function testConvertNullToDatabaseValueShouldSuccess(): void
    {
        // Given
        $value = null;

        // When
        $return = $this->uuidType->convertToDatabaseValue($value, $this->platform);

        // Then
        $this->assertTrue($return === null);
    }

    public function testConvertStringUuidToDatabaseValueShouldSuccess()
    {
        // Given
        $value = $this->validUuid;

        // When
        $return = $this->uuidType->convertToDatabaseValue($value, $this->platform);

        // Then
        $this->assertIsString($return);
        $this->assertEquals($return, $value);
    }

    public function testConvertNotValidStringUuidToDatabaseValueShouldSuccess()
    {
        // Given
        $value = "029232f6-da88-11ec-9d----0242ac120002"; // not valid uuid

        // Then
        $this->expectException(InvalidUuidException::class);

        // When
        $return = $this->uuidType->convertToDatabaseValue($value, $this->platform);
    }

    public function testConvertUuidInstanceToDatabaseValueShouldSuccess()
    {
        // Given
        $uuid = Uuid::fromString($this->validUuid);

        // When
        $return = $this->uuidType->convertToDatabaseValue($uuid, $this->platform);

        // Then
        $this->assertIsString($return);
        $this->assertEquals($return, $this->validUuid);
    }

    public function testConvertNotExpectedToDatabaseValueShouldFail(): void
    {
        // Given
        $value = 3223; // not valid

        // Then
        $this->expectException(ConversionException::class);

        // When
        $return = $this->uuidType->convertToDatabaseValue($value, $this->platform);
    }

    public function testConvertNullToPHPValueShouldSuccess(): void
    {
        // Given
        $value = null;

        // When
        $return = $this->uuidType->convertToPHPValue($value, $this->platform);

        // Then
        $this->assertTrue($return === null);
    }

    public function testConvertUuidInstanceToPHPValueShouldSuccess()
    {
        // Given
        $value = Uuid::fromString($this->validUuid);

        // When
        $return = $this->uuidType->convertToPHPValue($value, $this->platform);

        // Then
        $this->assertIsObject($return);
        $this->assertInstanceOf(Uuid::class, $return);
    }

    public function testConvertNoStringableValueToPHPValueShouldFail(): void
    {
        // Given
        $value = 3223; // not valid

        // Then
        $this->expectException(ConversionException::class);

        // When
        $return = $this->uuidType->convertToPHPValue($value, $this->platform);
    }

    public function testConvertNotValidUuidStringValueToPHPValueShouldFail(): void
    {
        // Given
        $value = 'ala ma kota'; // not valid

        // Then
        $this->expectException(ConversionException::class);

        // When
        $return = $this->uuidType->convertToPHPValue($value, $this->platform);
    }

    public function testConvertValidUuidStringValueToPHPValueShouldSuccess(): void
    {
        // Given
        $value = $this->validUuid; // not valid

        // When
        $return = $this->uuidType->convertToPHPValue($value, $this->platform);

        // Then
        $this->assertIsObject($return);
        $this->assertInstanceOf(Uuid::class, $return);
    }
}
