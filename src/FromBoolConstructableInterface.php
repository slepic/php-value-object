<?php declare(strict_types=1);

namespace Slepic\ValueObject;

interface FromBoolConstructableInterface
{
    public static function fromBool(bool $value): self;
}
