<?php

declare(strict_types=1);

namespace Slepic\Tests\ValueObject;

use PHPUnit\Framework\TestCase;
use Slepic\ValueObject\TypeError;

class TypeErrorTest extends TestCase
{
    /**
     * @param $value
     * @param string $expectedType
     *
     * @dataProvider provideTestData
     */
    public function testInstanceMethodParameterError($value, string $expectedType): void
    {
        $method = \lcfirst($expectedType);
        $methods = new TypeHintedMethodsFixture();
        try {
            $methods->$method($value);
            self::assertTrue(false, 'TypeError not thrown.');
        } catch (\TypeError $e) {
            $type = TypeError::getExpectedType($e);
            self::assertSame($expectedType, $type);
        }
    }

    /**
     * @param $value
     * @param string $expectedType
     *
     * @dataProvider provideTestData
     */
    public function testStaticMethodParameterError($value, string $expectedType): void
    {
        $method = lcfirst($expectedType) . 'Static';
        try {
            TypeHintedMethodsFixture::$method($value);
            self::assertTrue(false, 'TypeError not thrown.');
        } catch (\TypeError $e) {
            $type = TypeError::getExpectedType($e);
            self::assertSame($expectedType, $type);
        }
    }

    /**
     * @param $value
     * @param string $expectedType
     *
     * @dataProvider provideTestData
     */
    public function testAnonymousInstanceMethodParameterError($value, string $expectedType): void
    {
        $method = \lcfirst($expectedType);
        $methods = new class {
            public function int(int $value): void
            {
            }

            public function string(string $value): void
            {
            }

            public function array(array $value): void
            {
            }

            public function object(object $value): void
            {
            }

            public function dateTimeImmutable(\DateTimeImmutable $value): void
            {
            }
        };
        try {
            $methods->$method($value);
            self::assertTrue(false, 'TypeError not thrown.');
        } catch (\TypeError $e) {
            $type = TypeError::getExpectedType($e);
            self::assertSame($expectedType, $type);
        }
    }

    /**
     * @param $value
     * @param string $expectedType
     *
     * @dataProvider provideTestData
     */
    public function testInstancePropertyAssignmentError($value, string $expectedType): void
    {
        $property = \lcfirst($expectedType);
        $methods = new TypeHintedMethodsFixture();
        try {
            $methods->$property = $value;
            self::assertTrue(false, 'TypeError not thrown.');
        } catch (\TypeError $e) {
            $type = TypeError::getExpectedType($e);
            self::assertSame($expectedType, $type);
        }
    }

    /**
     * @param $value
     * @param string $expectedType
     *
     * @dataProvider provideTestData
     */
    public function testStaticPropertyAssignmentError($value, string $expectedType): void
    {
        $property = \lcfirst($expectedType) . 'Static';
        try {
            TypeHintedMethodsFixture::$$property = $value;
            self::assertTrue(false, 'TypeError not thrown.');
        } catch (\TypeError $e) {
            $type = TypeError::getExpectedType($e);
            self::assertSame($expectedType, $type);
        }
    }

    /**
     * @param $value
     * @param string $expectedType
     *
     * @dataProvider provideTestData
     */
    public function testAnonymousInstancePropertyAssignmentError($value, string $expectedType): void
    {
        $property = \lcfirst($expectedType);
        $methods = new class {
            public int $int;
            public string $string;
            public array $array;
            public object $object;
            public \DateTimeImmutable $dateTimeImmutable;
        };
        try {
            $methods->$property = $value;
            self::assertTrue(false, 'TypeError not thrown.');
        } catch (\TypeError $e) {
            $type = TypeError::getExpectedType($e);
            self::assertSame($expectedType, $type);
        }
    }

    public function provideTestData(): array
    {
        return [
            ['string value', 'int'],
            [[], 'int'],
            [new \stdClass(), 'int'],
            [new \DateTimeImmutable(), 'int'],

            ['string value', 'array'],
            [10, 'array'],
            [new \stdClass(), 'array'],
            [new \DateTimeImmutable(), 'array'],

            [1, 'string'],
            [[], 'string'],
            [new \stdClass(), 'string'],
            [new \DateTimeImmutable(), 'string'],

            [1, 'object'],
            [[], 'object'],
            ['string value', 'object'],

            [1, \DateTimeImmutable::class],
            [[], \DateTimeImmutable::class],
            ['string value', \DateTimeImmutable::class],
            [new \stdClass(), \DateTimeImmutable::class],
        ];
    }

    /**
     * @param $value
     * @param string $expectedType
     * @param callable $callback
     *
     * @dataProvider provideCallbackParameterTestData
     */
    public function testCallbackParameterError($value, string $expectedType, callable $callback): void
    {
        try {
            $callback($value);
            self::assertTrue(false, 'TypeError not thrown.');
        } catch (\TypeError $e) {
            $type = TypeError::getExpectedType($e);
            self::assertSame($expectedType, $type);
        }
    }

    public function provideCallbackParameterTestData(): array
    {
        return [

            // arrow functions

            ['string value', 'int', fn(int $v) => $v],
            [[], 'int', fn(int $v) => $v],
            [new \DateTimeImmutable(), 'int', fn(int $v) => $v],

            [1, 'string', fn(string $v) => $v],
            [[], 'string', fn(string $v) => $v],
            [new \DateTimeImmutable(), 'string', fn(string $v) => $v],

            [1, 'array', fn(array $v) => $v],
            ['string value', 'array', fn(array $v) => $v],
            [new \DateTimeImmutable(), 'array', fn(array $v) => $v],

            [1, 'object', fn(object $v) => $v],
            ['string value', 'object', fn(object $v) => $v],
            [[], 'object', fn(object $v) => $v],

            [1, \DateTimeImmutable::class, fn(\DateTimeImmutable $v) => $v],
            ['string value', \DateTimeImmutable::class, fn(\DateTimeImmutable $v) => $v],
            [[], \DateTimeImmutable::class, fn(\DateTimeImmutable $v) => $v],
            [new \stdClass(), \DateTimeImmutable::class, fn(\DateTimeImmutable $v) => $v],

            // closures

            ['string value', 'int', function (int $v) {
            }],
            [[], 'int', function (int $v) {
            }],
            [new \DateTimeImmutable(), 'int', function (int $v) {
            }],

            [1, 'string', function (string $v) {
            }],
            [[], 'string', function (string $v) {
            }],
            [new \DateTimeImmutable(), 'string', function (string $v) {
            }],

            [1, 'array', function (array $v) {
            }],
            ['string value', 'array', function (array $v) {
            }],
            [new \DateTimeImmutable(), 'array', function (array $v) {
            }],

            [1, 'object', function (object $v) {
            }],
            ['string value', 'object', function (object $v) {
            }],
            [[], 'object', function (object $v) {
            }],

            [1, \DateTimeImmutable::class, function (\DateTimeImmutable $v) {
            }],
            ['string value', \DateTimeImmutable::class, function (\DateTimeImmutable $v) {
            }],
            [[], \DateTimeImmutable::class, function (\DateTimeImmutable $v) {
            }],
            [new \stdClass(), \DateTimeImmutable::class, function (\DateTimeImmutable $v) {
            }],
        ];
    }
}
