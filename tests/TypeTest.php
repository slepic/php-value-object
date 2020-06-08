<?php declare(strict_types=1);

namespace Slepic\Tests\ValueObject;

use PHPUnit\Framework\TestCase;
use Slepic\ValueObject\Type;
use Slepic\ValueObject\Type\BoolType;
use Slepic\ValueObject\Type\IntType;
use Slepic\ValueObject\Type\FloatType;
use Slepic\ValueObject\Type\StringType;
use Slepic\ValueObject\Type\ArrayType;
use Slepic\ValueObject\Type\ClassType;

class TypeTest extends TestCase
{
    public function testBool(): void
    {
        self::assertInstanceOf(BoolType::class, Type::forBuiltinType('bool'));
    }

    public function testInt(): void
    {
        self::assertInstanceOf(IntType::class, Type::forBuiltinType('int'));
    }

    public function testFloat(): void
    {
        self::assertInstanceOf(FloatType::class, Type::forBuiltinType('float'));
    }

    public function testString(): void
    {
        self::assertInstanceOf(StringType::class, Type::forBuiltinType('string'));
    }

    public function testArray(): void
    {
        self::assertInstanceOf(ArrayType::class, Type::forBuiltinType('array'));
    }

    public function testClassNotValueObject(): void
    {
        self::assertInstanceOf(ClassType::class, Type::forClass(\DateTimeImmutable::class));
    }
}
