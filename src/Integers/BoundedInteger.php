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

        if ($value < $minValue || $value > $maxValue) {
            throw IntegerOutOfBounds::exception($minValue, $maxValue);
        }

        parent::__construct($value);
    }
}
