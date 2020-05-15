<?php declare(strict_types=1);

namespace Slepic\ValueObject;

interface FromObjectConstructableInterface
{
    public static function fromObject(object $value): self;
}
