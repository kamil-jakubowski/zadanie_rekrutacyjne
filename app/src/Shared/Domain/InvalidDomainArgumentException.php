<?php
declare(strict_types=1);

namespace App\Shared\Domain;

use InvalidArgumentException;
use Throwable;

/**
 * Invalid arguments given to Domain
 * @author Kamil Jakubowski <kamil.jakubowski@gmail.com>
 */
class InvalidDomainArgumentException extends InvalidArgumentException
{
    /**
     * Making constructor protected. Specific domain exception is better to create using static factories to delegate responsibility of handling the kind/message of error in Exception class. It's my understanding of SOLID in Exceptions. Many developers create child empty exceptions only for getting new type, but we should use it to encapsulate error handling.
     *
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    protected function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}