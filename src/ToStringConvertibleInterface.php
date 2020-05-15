<?php declare(strict_types=1);

namespace Slepic\ValueObject;

interface ToStringConvertibleInterface
{
    public function __toString(): string;
}
