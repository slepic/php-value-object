<?php declare(strict_types=1);

namespace Slepic\ValueObject;

interface ToFloatConvertibleInterface
{
    public function toFloat(): float;
}
