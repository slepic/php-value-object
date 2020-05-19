<?php declare(strict_types=1);

namespace Slepic\ValueObject\Type\Downcasting;

interface ToArrayConvertibleInterface
{
    public function toArray(): array;
}
