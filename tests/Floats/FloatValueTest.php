<?php declare(strict_types=1);

namespace Slepic\Tests\ValueObject\Floats;

use PHPUnit\Framework\TestCase;
use Slepic\ValueObject\Floats\FloatValue;
use Slepic\ValueObject\Type\Downcasting\ToArrayConvertibleInterface;
use Slepic\ValueObject\Type\Downcasting\ToBoolConvertibleInterface;
use Slepic\ValueObject\Type\Downcasting\ToFloatConvertibleInterface;
use Slepic\ValueObject\Type\Downcasting\ToIntConvertibleInterface;
use Slepic\ValueObject\Type\Downcasting\ToStringConvertibleInterface;

final class FloatValueTest extends TestCase
{
    public function testImplements(): void
    {
        $object = new FloatValue(11.1);
        self::assertInstanceOf(\JsonSerializable::class, $object);
        self::assertNotInstanceOf(ToBoolConvertibleInterface::class, $object);
        self::assertNotInstanceOf(ToIntConvertibleInterface::class, $object);
        self::assertInstanceOf(ToFloatConvertibleInterface::class, $object);
        self::assertNotInstanceOf(ToStringConvertibleInterface::class, $object);
        self::assertNotInstanceOf(ToArrayConvertibleInterface::class, $object);
    }

    public function testToInt(): void
    {
        $object = new FloatValue(11.1);
        self::assertSame(11, $object->toInt());
    }

    public function testToFloat(): void
    {
        $object = new FloatValue(11.1);
        self::assertSame(11.1, $object->toFloat());
    }

    public function testToString(): void
    {
        $object = new FloatValue(11.1);
        self::assertSame('11.1', (string) $object);
    }

    public function testJsonSerialization(): void
    {
        $object = new FloatValue(11.1);
        self::assertSame('11.1', \json_encode($object));
    }
}
