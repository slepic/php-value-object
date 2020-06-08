<?php declare(strict_types=1);

namespace Slepic\ValueObject\Floats;

use Slepic\ValueObject\ViolationException;
use Slepic\ValueObject\ViolationExceptionInterface;

final class FloatOutOfBounds extends FloatViolation
{
    private float $minValue;
    private float $maxValue;

    public function __construct(float $minValue, float $maxValue, string $message = '')
    {
        $this->minValue = $minValue;
        $this->maxValue = $maxValue;
        parent::__construct($message ?: "Expected value between $minValue and $maxValue.");
    }

    public function getMinValue(): float
    {
        return $this->minValue;
    }

    public function getMaxValue(): float
    {
        return $this->maxValue;
    }

    public static function exception(float $minValue, float $maxValue): ViolationExceptionInterface
    {
        return ViolationException::for(new self($minValue, $maxValue));
    }

    public static function check(float $minValue, float $maxValue, float $value): void
    {
        if ($value < $minValue || $value > $maxValue) {
            throw self::exception($minValue, $maxValue);
        }
    }
}
