<?php

namespace Slepic\ValueObject\Strings;

interface StringLengthExceptionInterface extends StringValueExceptionInterface
{
    public function getValueLength(): int;
}
