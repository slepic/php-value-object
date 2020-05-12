<?php declare(strict_types=1);

namespace Slepic\ValueObject\Strings;

interface StringLengthExceptionInterface extends StringValueExceptionInterface
{
    public function getValueLength(): int;
}
