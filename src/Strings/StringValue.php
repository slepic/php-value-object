<?php declare(strict_types=1);

namespace Slepic\ValueObject\Strings;

class StringValue implements \JsonSerializable
{
    private string $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    final public function __toString(): string
    {
        return $this->value;
    }

    final public function jsonSerialize(): string
    {
        return $this->value;
    }

    public function getLength(): int
    {
        return \strlen($this->value);
    }
}
