<?php declare(strict_types=1);

namespace Slepic\ValueObject\Floats;

class FloatTooSmallException extends FloatException implements FloatTooSmallExceptionInterface
{
    private float $lowerBound;

    public function __construct(
        float $lowerBound,
        float $value,
        ?string $expectation,
        string $message = "",
        int $code = 0,
        \Throwable $previous = null
    ) {
        $this->lowerBound = $lowerBound;
        parent::__construct($value, $expectation, $message, $code, $previous);
    }

    public function getLowerBound(): float
    {
        return $this->lowerBound;
    }
}
