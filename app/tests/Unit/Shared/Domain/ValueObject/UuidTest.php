<?php

namespace App\Tests\Unit\Shared\Domain\ValueObject;

use App\Shared\Domain\ValueObject\Exception\InvalidUuidException;
use App\Shared\Domain\ValueObject\Uuid;
use PHPUnit\Framework\TestCase;

class UuidTest extends TestCase
{
    private const VALID_UUID1 = 'e7b882f0-d942-11ec-9d64-0242ac120002';

    public function testUuidCreationFromStringUuid1WithValidStringSuccess(): void
    {
        // Given
        $uuidStringValid = self::VALID_UUID1;

        // When
        $uuid = Uuid::fromString($uuidStringValid);

        // Then
        $this->assertEquals($uuidStringValid, $uuid->toString());
    }

    public function testUuidCreationFromStringNotValidShouldFailed(): void
    {
        // Given
        $uuidString = 'ldfkdsjkfjdskhsdahsflsnfjkd';

        // Then
        $this->expectException(InvalidUuidException::class);

        // When
        Uuid::fromString($uuidString);
    }

    public function testUuidRandomCreationSuccessful(): void
    {
        // Given
        //none

        //When
        $uuid = Uuid::random();

        //Then
        // I know that this is a dependency in test, that should not be here, but it's better than hard code here regexps to check validity of UUID
        $this->assertTrue($uuid instanceof Uuid);
        $this->assertTrue( \Ramsey\Uuid\Uuid::isValid($uuid->toString()));
    }

    public function testUuidToStringSuccessful(): void
    {
        // Given
        $uuid = Uuid::fromString(self::VALID_UUID1);

        //When
        $stringMethod = $uuid->toString();
        $stringMethodMagic = (string) $uuid->toString();

        // Then
        $this->assertTrue(is_string($stringMethod));
        $this->assertTrue(is_string($stringMethodMagic));
        $this->assertEquals(self::VALID_UUID1, $stringMethod);
        $this->assertEquals(self::VALID_UUID1, $stringMethodMagic);
    }
}
