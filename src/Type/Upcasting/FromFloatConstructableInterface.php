<?php declare(strict_types=1);

namespace Slepic\ValueObject\Type\Upcasting;

interface FromFloatConstructableInterface
{
    public static function fromFloat(float $value): self;
}
