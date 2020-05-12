<?php declare(strict_types=1);

namespace Slepic\ValueObject\Strings;

use Slepic\ValueObject\InvalidValueExceptionInterface;

interface StringValueExceptionInterface extends InvalidValueExceptionInterface
{
    public function getValue(): string;
}
