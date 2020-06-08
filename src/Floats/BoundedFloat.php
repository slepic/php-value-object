<?php declare(strict_types=1);

namespace Slepic\ValueObject\Floats;

abstract class BoundedFloat extends FloatValue
{
    abstract protected static function minValue(): float;

    abstract protected static function maxValue(): float;

    public function __construct(float $value)
    {
        $minValue = static::minValue();
        $maxValue = static::maxValue();
        FloatOutOfBounds::check($minValue, $maxValue, $value);
        parent::__construct($value);
    }
}
