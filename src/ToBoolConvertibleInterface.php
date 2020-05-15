<?php declare(strict_types=1);

namespace Slepic\ValueObject;

interface ToBoolConvertibleInterface
{
    public function toBool(): bool;
}
