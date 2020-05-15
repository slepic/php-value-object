<?php declare(strict_types=1);

namespace Slepic\ValueObject\Integers;

use Slepic\ValueObject\InvalidValueException;

class IntegerException extends InvalidValueException implements IntegerExceptionInterface
{
    public function __construct(
        int $value,
        string $message = "",
        int $code = 0,
        \Throwable $previous = null
    ) {
        parent::__construct($value, $message, $code, $previous);
    }

    public function getValue(): int
    {
        return parent::getValue();
    }
}
