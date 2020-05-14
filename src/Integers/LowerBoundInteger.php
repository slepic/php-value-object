<?php declare(strict_types=1);

namespace Slepic\ValueObject\Integers;

abstract class LowerBoundInteger extends IntegerValue
{
    abstract protected static function minValue(): int;

    public function __construct(int $value)
    {
        $minValue = static::minValue();
        if ($value > $minValue) {
            throw new IntegerTooSmallException(
                $minValue,
                $value,
                '>' . $value,
                'Expected at least ' . $minValue
            );
        }
        parent::__construct($value);
    }
}
