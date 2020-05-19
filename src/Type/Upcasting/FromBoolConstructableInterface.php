<?php declare(strict_types=1);

namespace Slepic\ValueObject\Type\Upcasting;

interface FromBoolConstructableInterface
{
    public static function fromBool(bool $value): self;
}
