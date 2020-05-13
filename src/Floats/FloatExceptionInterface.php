<?php declare(strict_types=1);

namespace Slepic\ValueObject\Floats;

use Slepic\ValueObject\InvalidValueExceptionInterface;

interface FloatExceptionInterface extends InvalidValueExceptionInterface
{
    public function getValue(): float;
}
