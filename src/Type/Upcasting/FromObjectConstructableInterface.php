<?php declare(strict_types=1);

namespace Slepic\ValueObject\Type\Upcasting;

interface FromObjectConstructableInterface
{
    public static function fromObject(object $value): self;
}
