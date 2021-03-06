<?php declare(strict_types=1);

namespace Slepic\ValueObject\Integers;

abstract class UpperBoundInteger extends IntegerValue
{
    abstract protected static function maxValue(): int;

    public function __construct(int $value)
    {
        $maxValue = static::maxValue();
        if ($value > $maxValue) {
            throw IntegerTooBig::exception($maxValue);
        }
        parent::__construct($value);
    }
}
