<?php declare(strict_types=1);

namespace Slepic\Tests\ValueObject\Enum;

use Slepic\ValueObject\Enum\ConstantStringValuesEnum;

/**
 * @method static value1()
 * @method static value2()
 */
final class ConstantStringValuesEnumFixture extends ConstantStringValuesEnum
{
    public const VALUE_1 = 'value1';
    public const VALUE_2 = 'value2';
}
