<?php declare(strict_types=1);

namespace Slepic\ValueObject;

interface ToIntConvertibleInterface
{
    public function toInt(): int;
}
