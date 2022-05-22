<?php
declare(strict_types=1);

namespace App\Product\Domain;

use App\Product\Domain\Exception\InvalidProductNameException;
use App\Product\Domain\Exception\InvalidProductPriceException;
use App\Shared\Domain\Interfaces\DateCreationInterface;
use App\Shared\Domain\Traits\DateCreationTrait;
use \App\Shared\Domain\Interfaces\Entity as EntityInterface;
use App\Shared\Domain\Traits\Entity as EntityTrait;
use App\Shared\Domain\ValueObject\Uuid;
use DateTimeImmutable;

/**
 * Class {Product}
 * @author Kamil Jakubowski <kamil.jakubowski@gmail.com>
 */
class Product implements EntityInterface, DateCreationInterface
{
    public const MAX_NAME_LENGTH = 50;
    public const MIN_NAME_LENGTH = 3;
    public const MAX_PRODUCT_PRICE = 1000000.0;

    // Entity identify
    use EntityTrait;
    // information of date created
    use DateCreationTrait;

    /**
     * Name of the product.
     * Cannot be shorter than MIN_NAME_LENGTH
     * Cannot be longer that MAX_NAME_LENGTH
     * @var string
     */
    protected string $name;

    /**
     * Price of the product.
     * Cannot be zero.
     * Must be positive number.
     * Max float precision is 2.
     * Must be no greather than MAX_PRODUCT_PRICE
     *
     * @todo 1. It could be a ValueObject Money, to encapsulate its validation
     * @todo 2. For recruitment task purposes and from its specification I've assumed that all prices can be only in one currency. If curencies could be different Money ValueObject would be all the more neccessary, to encapsulate price and currency
     * @todo 3. For more "financal" purposes I will definetly avoid using pure floats(even when float is a double in PHP actually). I will use some library that imitate lack of DOUBLES in PHP: e.q. http://php-decimal.io OR https://github.com/brick/money and hide that dependency it in ValueObject interface to make sure that only one object in whole domain depends on 3rd party and rest of domain use only ValueObject interface.
     *
     * @var float
     */
    protected float $price;

    /**
     * Product constructor
     * Domain object constructor should be private/protected and created only by static factories to encapsulate identity (id uuid) and metadata
     * Infrastructure child object can create another methods to override/set identity of already persisted entity in the infrastructure (like Doctrine ORM entity proxies do for example)
     *
     * @param string $name
     * @param float $price
     * @param int|null $id
     * @param Uuid|null $uuid
     * @param DateTimeImmutable|null $createdDate
     */
    protected function __construct(
        string $name,
        float $price,
        ?int $id = null,
        ?Uuid $uuid = null,
        ?DateTimeImmutable $createdDate = null
    )
    {
        $this->setupIdentity($id, $uuid);
        $this->setupCreatedDate($createdDate);

        $this->setName($name);
        $this->setPrice($price);
    }

    /**
     * Static factory
     * @param string $name
     * @param float $price
     * @return static
     */
    static public function createNew(string $name, float $price) : static
    {
        return new static($name, $price);
    }

    /**
     * @param string $name
     * @return void
     */
    public function setName(string $name): void
    {
        $this->validateName($name);
        $this->name = $name;
    }

    /**
     * @param float $price
     * @return void
     */
    public function setPrice(float $price): void
    {
        $this->validatePrice($price);
        $this->price = $price;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @param string $name
     * @return void
     * @throws  InvalidProductNameException when name is incorrect
     */
    protected function validateName(string $name): void
    {
        if (strlen($name) > static::MAX_NAME_LENGTH) {
            throw InvalidProductNameException::create($name, InvalidProductNameException::CODE_NAME_TOO_LONG);
        }

        if (strlen($name) < static::MIN_NAME_LENGTH) {
            throw InvalidProductNameException::create($name, InvalidProductNameException::CODE_NAME_TOO_SHORT);
        }
    }

    /**
     * @param float $price
     * @return void
     * @throws  InvalidProductPriceException when price is incorrect
     */
    protected function validatePrice(float $price): void
    {
        $this->checkIfPriceIsTooHigh($price);
        $this->checkIfPriceIsPositiveAndGreaterThanZero($price);
        $this->checkPricePrecision($price);
    }

    /**
     * @param float $price
     * @return void
     * @throws  InvalidProductPriceException when price is too high
     */
    protected function checkIfPriceIsTooHigh(float $price): void
    {
        if ($price > static::MAX_PRODUCT_PRICE) {
            throw InvalidProductPriceException::create($price, InvalidProductPriceException::CODE_PRICE_TOO_HIGH);
        }
    }

    /**
     * @param float $price
     * @return void
     * @throws  InvalidProductPriceException when price not positive or less than zero
     */
    protected function checkIfPriceIsPositiveAndGreaterThanZero(float $price): void
    {
        if ($price === 0.0) {
            throw InvalidProductPriceException::create($price, InvalidProductPriceException::CODE_PRICE_ZERO);
        }

        if ($price < 0.0) {
            throw InvalidProductPriceException::create($price, InvalidProductPriceException::CODE_PRICE_NEGATIVE);
        }
    }

    /**
     * @param float $price
     * @return void
     * @throws InvalidProductPriceException when price precision is greater than 2
     */
    protected function checkPricePrecision(float $price): void
    {
        $stringPrice = (string) $price;
        $afterDot = strrchr($stringPrice, '.');
        $precision = $afterDot ? strlen(substr($afterDot, 1)) : 0;

        if ($precision > 2) {
            throw InvalidProductPriceException::create($price, InvalidProductPriceException::CODE_PRICE_TOO_HIGH_PRECISION);
        }
    }
}