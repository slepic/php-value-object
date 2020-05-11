<?php

namespace Slepic\ValueObject\Strings;

interface StringTooLongExceptionInterface extends StringLengthExceptionInterface
{
    public function getMaxLength(): int;
}
