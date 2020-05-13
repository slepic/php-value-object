<?php declare(strict_types=1);

namespace Slepic\ValueObject\Integers;

use Slepic\ValueObject\InvalidValueExceptionInterface;

interface IntegerExceptionInterface extends InvalidValueExceptionInterface
{
    public function getValue(): int;
}
