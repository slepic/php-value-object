<?php declare(strict_types=1);

namespace Slepic\ValueObject\Integers;

class IntegerTooSmallException extends IntegerException implements IntegerTooSmallExceptionInterface
{
    private int $lowerBound;

    public function __construct(
        int $lowerBound,
        int $value,
        string $message = "",
        int $code = 0,
        \Throwable $previous = null
    ) {
        $this->lowerBound = $lowerBound;
        parent::__construct($value, $message, $code, $previous);
    }

    public function getLowerBound(): int
    {
        return $this->lowerBound;
    }
}
