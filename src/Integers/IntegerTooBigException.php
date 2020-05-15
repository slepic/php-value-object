<?php declare(strict_types=1);

namespace Slepic\ValueObject\Integers;

class IntegerTooBigException extends IntegerException implements IntegerTooBigExceptionInterface
{
    private int $upperBound;

    public function __construct(
        int $upperBound,
        int $value,
        string $message = "",
        int $code = 0,
        \Throwable $previous = null
    ) {
        $this->upperBound = $upperBound;
        parent::__construct($value, $message, $code, $previous);
    }

    public function getUpperBound(): int
    {
        return $this->upperBound;
    }
}
