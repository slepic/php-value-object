<?php declare(strict_types=1);

namespace Slepic\ValueObject\Strings;

use Slepic\ValueObject\InvalidValueException;

class StringException extends InvalidValueException implements StringExceptionInterface
{
    public function __construct(
        string $value,
        string $message = "",
        int $code = 0,
        \Throwable $previous = null
    ) {
        parent::__construct($value, $message, $code, $previous);
    }

    public function getValue(): string
    {
        return parent::getValue();
    }
}
