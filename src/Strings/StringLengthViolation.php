<?php declare(strict_types=1);

namespace Slepic\ValueObject\Strings;

use Slepic\ValueObject\ViolationInterface;

class StringLengthViolation implements ViolationInterface
{
    private int $length;

    public function __construct(int $length)
    {
        $this->length = $length;
    }

    final public function getLength(): int
    {
        return $this->length;
    }
}
