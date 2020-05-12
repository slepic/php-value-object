<?php declare(strict_types=1);

namespace Slepic\Tests\ValueObject\Enum;

use Slepic\ValueObject\Enum\NamedConstructorsEnum;

final class NamedConstructorsEnumFixture extends NamedConstructorsEnum
{
    public static function value1(): self
    {
        return self::__(__METHOD__);
    }

    public static function value2(): self
    {
        return self::__(__METHOD__);
    }
}
