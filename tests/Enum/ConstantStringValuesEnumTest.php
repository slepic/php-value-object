<?php declare(strict_types=1);

namespace Slepic\Tests\ValueObject\Enum;

use PHPUnit\Framework\TestCase;
use Slepic\ValueObject\Strings\StringExceptionInterface;

final class ConstantStringValuesEnumTest extends TestCase
{
    public function testNamedConstructor(): void
    {
        $value1 = ConstantStringValuesEnumFixture::value1();
        self::assertInstanceOf(ConstantStringValuesEnumFixture::class, $value1);
        self::assertSame('value1', (string) $value1);

        $value2 = ConstantStringValuesEnumFixture::value2();
        self::assertInstanceOf(ConstantStringValuesEnumFixture::class, $value2);
        self::assertSame('value2', (string) $value2);
    }

    public function testUniqueInstances(): void
    {
        self::assertSame(
            ConstantStringValuesEnumFixture::value1(),
            ConstantStringValuesEnumFixture::value1()
        );

        self::assertSame(
            ConstantStringValuesEnumFixture::value2(),
            ConstantStringValuesEnumFixture::value2()
        );
    }

    public function testGetAll(): void
    {
        $all = ConstantStringValuesEnumFixture::all();
        self::assertCount(2, $all);
        self::assertArrayHasKey('value1', $all);
        self::assertSame(ConstantStringValuesEnumFixture::value1(), $all['value1']);
        self::assertArrayHasKey('value2', $all);
        self::assertSame(ConstantStringValuesEnumFixture::value2(), $all['value2']);
    }

    public function testFromString(): void
    {
        self::assertSame(
            ConstantStringValuesEnumFixture::value1(),
            ConstantStringValuesEnumFixture::fromString('value1')
        );

        self::assertSame(
            ConstantStringValuesEnumFixture::value2(),
            ConstantStringValuesEnumFixture::fromString('value2')
        );

        try {
            ConstantStringValuesEnumFixture::fromString('invalid');
            self::assertTrue(false, 'StringValueExceptionInterface not thrown');
        } catch (StringExceptionInterface $e) {
            self::assertSame('invalid', $e->getValue());
            self::assertStringContainsString('value1', $e->getExpectation());
            self::assertStringContainsString('value2', $e->getExpectation());
        }
    }
}
