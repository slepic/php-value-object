<?php declare(strict_types=1);

namespace Slepic\ValueObject\Strings;

class StringTooShortException extends StringLengthException implements StringTooShortExceptionInterface
{
    private int $minLength;

    public function __construct(
        int $minLength,
        int $valueLength,
        string $value,
        ?string $expectation = null,
        string $message = "",
        int $code = 0,
        \Throwable $previous = null
    ) {
        $message = $message ?: "Value must be at least $minLength characters long. Got $valueLength.";
        $this->minLength = $minLength;
        parent::__construct($valueLength, $value, $expectation, $message, $code, $previous);
    }

    final public function getMinLength(): int
    {
        return $this->minLength;
    }
}
