<?php declare(strict_types=1);

namespace Slepic\Tests\ValueObject\Strings;

use PHPUnit\Framework\TestCase;
use Slepic\ValueObject\Strings\StringValue;
use Slepic\ValueObject\Type\Downcasting\ToArrayConvertibleInterface;
use Slepic\ValueObject\Type\Downcasting\ToBoolConvertibleInterface;
use Slepic\ValueObject\Type\Downcasting\ToFloatConvertibleInterface;
use Slepic\ValueObject\Type\Downcasting\ToIntConvertibleInterface;
use Slepic\ValueObject\Type\Downcasting\ToStringConvertibleInterface;

final class StringValueTest extends TestCase
{
    public function testImplements(): void
    {
        $object = new StringValue('test');
        self::assertInstanceOf(\JsonSerializable::class, $object);
        self::assertNotInstanceOf(ToBoolConvertibleInterface::class, $object);
        self::assertNotInstanceOf(ToIntConvertibleInterface::class, $object);
        self::assertNotInstanceOf(ToFloatConvertibleInterface::class, $object);
        self::assertInstanceOf(ToStringConvertibleInterface::class, $object);
        self::assertNotInstanceOf(ToArrayConvertibleInterface::class, $object);
    }

    public function testToString(): void
    {
        $object = new StringValue('test');
        self::assertSame('test', (string) $object);
    }

    public function testJsonSerialization(): void
    {
        $object = new StringValue('test');
        self::assertSame('"test"', \json_encode($object));
    }

    public function testLength(): void
    {
        $object = new StringValue('test');
        self::assertSame(4, $object->getLength());
    }
}
