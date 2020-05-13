<?php declare(strict_types=1);

namespace Slepic\ValueObject\Integers;

interface IntegerTooBigExceptionInterface extends IntegerExceptionInterface
{
    public function getUpperBound(): int;
}
