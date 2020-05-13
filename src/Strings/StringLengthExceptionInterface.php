<?php declare(strict_types=1);

namespace Slepic\ValueObject\Strings;

interface StringLengthExceptionInterface extends StringExceptionInterface
{
    public function getValueLength(): int;
}
