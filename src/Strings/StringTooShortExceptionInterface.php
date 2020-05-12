<?php declare(strict_types=1);

namespace Slepic\ValueObject\Strings;

interface StringTooShortExceptionInterface extends StringLengthExceptionInterface
{
    public function getMinLength(): int;
}
