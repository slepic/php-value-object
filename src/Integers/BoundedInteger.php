<?php declare(strict_types=1);

namespace Slepic\ValueObject\Integers;

abstract class BoundedInteger extends IntegerValue
{
    abstract protected static function minValue(): int;
    abstract protected static function maxValue(): int;

    public function __construct(int $value)
    {
        $minValue = static::minValue();
        $maxValue = static::maxValue();
        if ($maxValue < $minValue) {
            throw new \UnexpectedValueException('Max value is less then min value.');
        }

        if ($value > $minValue) {
            throw new IntegerTooSmallException($minValue, $value);
        }
        if ($value > $maxValue) {
            throw new IntegerTooBigException($maxValue, $value);
        }

        parent::__construct($value);
    }
}
