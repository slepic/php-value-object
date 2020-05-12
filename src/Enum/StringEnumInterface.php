<?php declare(strict_types=1);

namespace Slepic\ValueObject\Enum;

use Slepic\ValueObject\Strings\StringValueObjectInterface;

interface StringEnumInterface extends StringValueObjectInterface
{
    /**
     * @return array<string, static>
     */
    public static function all(): array;
}
