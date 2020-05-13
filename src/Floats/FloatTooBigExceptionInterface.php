<?php declare(strict_types=1);

namespace Slepic\ValueObject\Floats;

interface FloatTooBigExceptionInterface extends FloatExceptionInterface
{
    public function getUpperBound(): float;
}
