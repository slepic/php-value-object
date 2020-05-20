<?php declare(strict_types=1);

namespace Slepic\Tests\ValueObject\Integers;

use PHPUnit\Framework\TestCase;
use Slepic\ValueObject\Integers\IntegerValue;
use Slepic\ValueObject\Type\Downcasting\ToArrayConvertibleInterface;
use Slepic\ValueObject\Type\Downcasting\ToBoolConvertibleInterface;
use Slepic\ValueObject\Type\Downcasting\ToFloatConvertibleInterface;
use Slepic\ValueObject\Type\Downcasting\ToIntConvertibleInterface;
use Slepic\ValueObject\Type\Downcasting\ToStringConvertibleInterface;

final class IntegerValueTest extends TestCase
{
    public function testImplements(): void
    {
        $object = new IntegerValue(10);
        self::assertInstanceOf(\JsonSerializable::class, $object);
        self::assertInstanceOf(ToIntConvertibleInterface::class, $object);
        self::assertNotInstanceOf(ToFloatConvertibleInterface::class, $object);
        self::assertNotInstanceOf(ToStringConvertibleInterface::class, $object);
        self::assertNotInstanceOf(ToArrayConvertibleInterface::class, $object);
        self::assertNotInstanceOf(ToBoolConvertibleInterface::class, $object);
    }

    public function testToInt(): void
    {
        $object = new IntegerValue(10);
        self::assertSame(10, $object->toInt());
    }
    public function testToFloat(): void
    {
        $object = new IntegerValue(11);
        self::assertSame(11.0, $object->toFloat());
    }

    public function testToString(): void
    {
        $object = new IntegerValue(10);
        self::assertSame('10', (string) $object);
    }

    public function testJsonSerialization(): void
    {
        $object = new IntegerValue(10);
        self::assertSame('10', \json_encode($object));
    }
}
