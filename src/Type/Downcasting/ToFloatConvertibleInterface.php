<?php declare(strict_types=1);

namespace Slepic\ValueObject\Type\Downcasting;

interface ToFloatConvertibleInterface
{
    public function toFloat(): float;
}
