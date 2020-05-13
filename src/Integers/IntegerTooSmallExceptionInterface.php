<?php declare(strict_types=1);

namespace Slepic\ValueObject\Integers;

interface IntegerTooSmallExceptionInterface extends IntegerExceptionInterface
{
    public function getLowerBound(): int;
}
