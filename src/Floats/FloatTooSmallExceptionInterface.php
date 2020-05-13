<?php declare(strict_types=1);

namespace Slepic\ValueObject\Floats;

interface FloatTooSmallExceptionInterface extends FloatExceptionInterface
{
    public function getLowerBound(): float;
}
