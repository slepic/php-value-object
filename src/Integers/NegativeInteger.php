<?php declare(strict_types=1);

namespace Slepic\ValueObject\Integers;

class NegativeInteger extends UpperBoundInteger
{
    final protected static function maxValue(): int
    {
        return -1;
    }
}
