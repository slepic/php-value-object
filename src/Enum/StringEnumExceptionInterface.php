<?php declare(strict_types=1);

namespace Slepic\ValueObject\Enum;

use Slepic\ValueObject\Strings\StringExceptionInterface;

/**
 * @template-extends EnumExceptionInterface<string>
 */
interface StringEnumExceptionInterface extends
    EnumExceptionInterface,
    StringExceptionInterface
{
    public function getValue(): string;

    /**
     * @return array<string>
     */
    public function getAllowedValues(): array;
}
