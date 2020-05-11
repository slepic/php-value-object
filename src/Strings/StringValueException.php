<?php

namespace Slepic\ValueObject\Strings;

use Slepic\ValueObject\InvalidValueException;

class StringValueException extends InvalidValueException implements StringValueExceptionInterface
{
    public function __construct(
        string $value,
        ?string $expectation,
        string $message = "",
        int $code = 0,
        \Throwable $previous = null
    ) {
        parent::__construct($value, $expectation, $message, $code, $previous);
    }

    public function getValue(): string
    {
        return parent::getValue();
    }
}
