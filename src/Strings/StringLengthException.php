<?php declare(strict_types=1);

namespace Slepic\ValueObject\Strings;

class StringLengthException extends StringValueException implements StringLengthExceptionInterface
{
    private int $valueLength;

    public function __construct(
        int $valueLength,
        string $value,
        ?string $expectation,
        string $message = "",
        int $code = 0,
        \Throwable $previous = null
    ) {
        $message = $message ?: "Value has unexpected length of $valueLength.";
        $this->valueLength = $valueLength;
        parent::__construct($value, $expectation, $message, $code, $previous);
    }

    final public function getValueLength(): int
    {
        return $this->valueLength;
    }
}
