<?php declare(strict_types=1);

namespace Slepic\ValueObject\Type\Upcasting;

interface FromIntConstructableInterface
{
    public static function fromInt(int $value): self;
}
