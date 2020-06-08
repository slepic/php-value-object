<?php declare(strict_types=1);

namespace Slepic\ValueObject\Integers;

use Slepic\ValueObject\ViolationException;
use Slepic\ValueObject\ViolationExceptionInterface;

final class IntegerOutOfBounds extends IntegerViolation
{
    private int $minValue;
    private int $maxValue;

    public function __construct(int $minValue, int $maxValue, string $message = '')
    {
        $this->minValue = $minValue;
        $this->maxValue = $maxValue;
        parent::__construct($message ?: "Integer value is out of bounds [$minValue, $maxValue].");
    }

    public function getMinValue(): int
    {
        return $this->minValue;
    }

    public function getMaxValue(): int
    {
        return $this->maxValue;
    }

    public static function exception(int $minValue, int $maxValue): ViolationExceptionInterface
    {
        return ViolationException::for(new self($minValue, $maxValue));
    }
}
