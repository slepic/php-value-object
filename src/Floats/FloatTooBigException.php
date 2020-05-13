<?php declare(strict_types=1);

namespace Slepic\ValueObject\Floats;

class FloatTooBigException extends FloatException implements FloatTooBigExceptionInterface
{
    private float $upperBound;

    public function __construct(
        float $upperBound,
        float $value,
        ?string $expectation,
        string $message = "",
        int $code = 0,
        \Throwable $previous = null
    ) {
        $this->upperBound = $upperBound;
        parent::__construct($value, $expectation, $message, $code, $previous);
    }

    public function getUpperBound(): float
    {
        return $this->upperBound;
    }
}
