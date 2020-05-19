<?php declare(strict_types=1);

namespace Slepic\Tests\ValueObject\Type;

use PHPUnit\Framework\TestCase;
use Slepic\ValueObject\Type\Downcasting\ToFloatConvertibleInterface;
use Slepic\ValueObject\Type\FloatType;
use Slepic\ValueObject\Type\TypeViolation;
use Slepic\ValueObject\ViolationExceptionInterface;

final class FloatTypeTest extends TestCase
{
    private FloatType $type;

    protected function setUp(): void
    {
        parent::setUp();
        $this->type = new FloatType();
    }

    public function testTypeExpectation(): void
    {
        $expectation = $this->type->getExpectation();
        self::assertFalse($expectation->acceptsNull());
        self::assertFalse($expectation->acceptsInt());
        self::assertFalse($expectation->acceptsString());
        self::assertTrue($expectation->acceptsFloat());
        self::assertFalse($expectation->acceptsBool());
        self::assertFalse($expectation->acceptsArray());
        // @todo objects
    }

    public function testFloatSuccess(): void
    {
        self::assertSame(11.1, $this->type->prepareValue(11.1));
    }

    public function testConvertibleToFloatSuccess(): void
    {
        $input = new class () implements ToFloatConvertibleInterface {
            public function toFloat(): float
            {
                return 11.1;
            }
        };
        self::assertSame(11.1, $this->type->prepareValue($input));
    }

    public function testConvertibleToFloatWithoutInterfaceFails(): void
    {
        $input = new class () {
            public function toFloat(): float
            {
                return 11.1;
            }
        };
        try {
            $this->type->prepareValue($input);
            self::assertTrue(false, 'Exception not thrown.');
        } catch (ViolationExceptionInterface $e) {
            $violations = $e->getViolations();
            self::assertCount(1, $violations);
            self::assertInstanceOf(TypeViolation::class, \reset($violations));
        }
    }

    public function testNullFails(): void
    {
        try {
            $this->type->prepareValue(null);
            self::assertTrue(false, 'Exception not thrown.');
        } catch (ViolationExceptionInterface $e) {
            $violations = $e->getViolations();
            self::assertCount(1, $violations);
            self::assertInstanceOf(TypeViolation::class, \reset($violations));
        }
    }

    public function testStringFails(): void
    {
        try {
            $this->type->prepareValue('');
            self::assertTrue(false, 'Exception not thrown.');
        } catch (ViolationExceptionInterface $e) {
            $violations = $e->getViolations();
            self::assertCount(1, $violations);
            self::assertInstanceOf(TypeViolation::class, \reset($violations));
        }
    }

    public function testIntFails(): void
    {
        try {
            $this->type->prepareValue(10);
            self::assertTrue(false, 'Exception not thrown.');
        } catch (ViolationExceptionInterface $e) {
            $violations = $e->getViolations();
            self::assertCount(1, $violations);
            self::assertInstanceOf(TypeViolation::class, \reset($violations));
        }
    }

    public function testBoolFails(): void
    {
        try {
            $this->type->prepareValue(true);
            self::assertTrue(false, 'Exception not thrown.');
        } catch (ViolationExceptionInterface $e) {
            $violations = $e->getViolations();
            self::assertCount(1, $violations);
            self::assertInstanceOf(TypeViolation::class, \reset($violations));
        }
    }

    public function testArrayFails(): void
    {
        try {
            $this->type->prepareValue([]);
            self::assertTrue(false, 'Exception not thrown.');
        } catch (ViolationExceptionInterface $e) {
            $violations = $e->getViolations();
            self::assertCount(1, $violations);
            self::assertInstanceOf(TypeViolation::class, \reset($violations));
        }
    }

    public function testObjectFails(): void
    {
        try {
            $this->type->prepareValue((object) []);
            self::assertTrue(false, 'Exception not thrown.');
        } catch (ViolationExceptionInterface $e) {
            $violations = $e->getViolations();
            self::assertCount(1, $violations);
            self::assertInstanceOf(TypeViolation::class, \reset($violations));
        }
    }

    public function testStringableObjectFails(): void
    {
        try {
            $this->type->prepareValue(new class () {
                public function __toString(): string
                {
                    return '11.1';
                }
            });
            self::assertTrue(false, 'Exception not thrown.');
        } catch (ViolationExceptionInterface $e) {
            $violations = $e->getViolations();
            self::assertCount(1, $violations);
            self::assertInstanceOf(TypeViolation::class, \reset($violations));
        }
    }
}
