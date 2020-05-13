<?php declare(strict_types=1);

namespace Slepic\ValueObject\Floats;

class FloatValue implements \JsonSerializable
{
    private float $value;

    public function __construct(float $value)
    {
        $this->value = $value;
    }

    final public function toFloat(): float
    {
        return $this->value;
    }

    final public function toInt(): int
    {
        return (int) $this->value;
    }

    final public function __toString(): string
    {
        return (string) $this->value;
    }

    final public function jsonSerialize(): float
    {
        return $this->value;
    }
}
