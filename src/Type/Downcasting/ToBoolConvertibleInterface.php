<?php declare(strict_types=1);

namespace Slepic\ValueObject\Type\Downcasting;

interface ToBoolConvertibleInterface
{
    public function toBool(): bool;
}
