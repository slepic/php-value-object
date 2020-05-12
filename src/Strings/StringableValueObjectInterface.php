<?php declare(strict_types=1);

namespace Slepic\ValueObject\Strings;

interface StringableValueObjectInterface extends \JsonSerializable
{
    public static function fromString(string $value): self;
    public function __toString(): string;
}
