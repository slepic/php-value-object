<?php declare(strict_types=1);

namespace Slepic\Tests\ValueObject\Type;

use PHPUnit\Framework\TestCase;
use Slepic\ValueObject\DateTime\DateTimeValue;
use Slepic\ValueObject\Type\ClassType;

final class ClassTypeTest extends TestCase
{
    public function testFromObjectConstructableOk(): void
    {
        $input = \DateTimeImmutable::createFromFormat(\DATE_ATOM, '2020-05-26T13:14:15+00:00');
        $type = new ClassType(new \ReflectionClass(DateTimeValue::class));
        $value = $type->prepareValue($input);
        self::assertInstanceOf(DateTimeValue::class, $value);
        self::assertSame('2020-05-26T13:14:15+00:00', (string) $value);
    }
}
