<?php declare(strict_types=1);

namespace Slepic\ValueObject\Type\Downcasting;

interface ToIntConvertibleInterface
{
    public function toInt(): int;
}
