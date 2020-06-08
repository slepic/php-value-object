<?php declare(strict_types=1);

namespace Slepic\ValueObject\Type\Upcasting;

interface FromAnyConstructableInterface
{
    /**
     * @param mixed $value
     * @return static
     */
    public static function fromAny($value): self;
}
