<?php declare(strict_types=1);

namespace Slepic\ValueObject\Integers;

use Slepic\ValueObject\ImmutableObjectTrait;
use Slepic\ValueObject\Type\Downcasting\ToIntConvertibleInterface;

class IntegerValue implements \JsonSerializable, ToIntConvertibleInterface
{
    use ImmutableObjectTrait;

    private int $value;

    public function __construct(int $value)
    {
        $this->value = $value;
    }

    final public function toInt(): int
    {
        return $this->value;
    }

    final public function toFloat(): float
    {
        return (float) $this->value;
    }

    final public function __toString(): string
    {
        return (string) $this->value;
    }

    final public function jsonSerialize(): int
    {
        return $this->value;
    }
}
