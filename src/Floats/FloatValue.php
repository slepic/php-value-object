<?php declare(strict_types=1);

namespace Slepic\ValueObject\Floats;

use Slepic\ValueObject\ImmutableObjectTrait;
use Slepic\ValueObject\Type\Downcasting\ToFloatConvertibleInterface;

class FloatValue implements \JsonSerializable, ToFloatConvertibleInterface
{
    use ImmutableObjectTrait;

    private float $value;

    public function __construct(float $value)
    {
        $this->value = $value;
    }

    final public function toFloat(): float
    {
        return $this->value;
    }

    public function toInt(): int
    {
        return (int) $this->value;
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }

    final public function jsonSerialize(): float
    {
        return $this->value;
    }
}
