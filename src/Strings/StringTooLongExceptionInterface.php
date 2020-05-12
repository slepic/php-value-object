<?php declare(strict_types=1);

namespace Slepic\ValueObject\Strings;

interface StringTooLongExceptionInterface extends StringLengthExceptionInterface
{
    public function getMaxLength(): int;
}
