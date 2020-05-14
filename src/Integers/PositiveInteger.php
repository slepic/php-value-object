<?php declare(strict_types=1);

namespace Slepic\ValueObject\Integers;

class PositiveInteger extends LowerBoundInteger
{
    final protected static function minValue(): int
    {
        return 1;
    }
}
