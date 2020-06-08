<?php declare(strict_types=1);

namespace Slepic\ValueObject\Type\Upcasting;

interface FromStringConstructableInterface
{
    public static function fromString(string $value): self;
}
