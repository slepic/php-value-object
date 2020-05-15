<?php declare(strict_types=1);

namespace Slepic\ValueObject;

interface ToArrayConvertibleInterface
{
    public function toArray(): array;
}
