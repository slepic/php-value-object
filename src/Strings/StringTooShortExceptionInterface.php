<?php

namespace Slepic\ValueObject\Strings;

interface StringTooShortExceptionInterface extends StringLengthExceptionInterface
{
    public function getMinLength(): int;
}
