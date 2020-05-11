<?php

namespace Slepic\ValueObject\Strings;

class StringTooLongException extends StringLengthException implements StringTooLongExceptionInterface
{
    private int $maxLength;

    public function __construct(
        int $maxLength,
        int $valueLength,
        string $value,
        ?string $expectation = null,
        string $message = "",
        int $code = 0,
        \Throwable $previous = null
    ) {
        $message = $message ?: "Value must be at most $maxLength characters long. Got $valueLength.";
        $this->maxLength = $maxLength;
        parent::__construct($valueLength, $value, $expectation, $message, $code, $previous);
    }

    final public function getMaxLength(): int
    {
        return $this->maxLength;
    }
}
