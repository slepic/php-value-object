<?php declare(strict_types=1);

namespace Slepic\ValueObject\Integers;

use Slepic\ValueObject\ViolationException;
use Slepic\ValueObject\ViolationExceptionInterface;
use Slepic\ValueObject\ViolationInterface;

final class IntegerOutOfBounds implements ViolationInterface
{
    private int $minValue;
    private int $maxValue;

    public function __construct(int $minValue, int $maxValue)
    {
        $this->minValue = $minValue;
        $this->maxValue = $maxValue;
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
        return new ViolationException([new self($minValue, $maxValue)]);
    }
}
