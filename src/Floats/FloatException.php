<?php declare(strict_types=1);

namespace Slepic\ValueObject\Floats;

use Slepic\ValueObject\InvalidValueException;

class FloatException extends InvalidValueException implements FloatExceptionInterface
{
    public function __construct(
        float $value,
        ?string $expectation,
        string $message = "",
        int $code = 0,
        \Throwable $previous = null
    ) {
        parent::__construct($value, $expectation, $message, $code, $previous);
    }

    public function getValue(): float
    {
        return parent::getValue();
    }
}
