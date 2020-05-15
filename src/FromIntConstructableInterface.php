<?php declare(strict_types=1);

namespace Slepic\ValueObject;

interface FromIntConstructableInterface
{
    public static function fromInt(int $value): self;
}
