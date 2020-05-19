<?php declare(strict_types=1);

namespace Slepic\ValueObject\Enum;

use Slepic\ValueObject\Strings\StringViolationInterface;

interface StringEnumViolationInterface extends StringViolationInterface
{
    /**
     * @return array<string>
     */
    public function getAllowedValues(): array;
}
