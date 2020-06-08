<?php declare(strict_types=1);

namespace Slepic\ValueObject\Type\Downcasting;

interface ToStringConvertibleInterface
{
    public function __toString(): string;
}
