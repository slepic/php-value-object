<?php declare(strict_types=1);

namespace Slepic\ValueObject\Enum;

use Slepic\ValueObject\InvalidValueExceptionInterface;

/**
 * @template T
 */
interface EnumExceptionInterface extends InvalidValueExceptionInterface
{
    /**
     * @return array
     * @template-return array<T>
     */
    public function getAllowedValues(): array;
}
