<?php

declare(strict_types=1);

namespace Slepic\ValueObject\Strings;

interface StringValueObjectInterface extends StringableValueObjectInterface
{

    public static function fromString(string $value): self;

    public function jsonSerialize(): string;
}
