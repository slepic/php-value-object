<?php declare(strict_types=1);

namespace Slepic\ValueObject\Integers;

class UnsignedInteger extends LowerBoundInteger
{
    final protected static function minValue(): int
    {
        return 0;
    }
}
