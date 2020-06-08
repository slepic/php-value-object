<?php declare(strict_types=1);

namespace Slepic\Tests\ValueObject\Type;

use PHPUnit\Framework\TestCase;
use Slepic\ValueObject\Type\BoolType;
use Slepic\ValueObject\Type\Downcasting\ToBoolConvertibleInterface;
use Slepic\ValueObject\Type\TypeViolation;
use Slepic\ValueObject\ViolationExceptionInterface;

final class BoolTypeTest extends TestCase
{
    private BoolType $type;

    protected function setUp(): void
    {
        parent::setUp();
        $this->type = new BoolType();
    }

    public function testTypeExpectation(): void
    {
        $expectation = $this->type->getExpectation();
        self::assertFalse($expectation->acceptsNull());
        self::assertFalse($expectation->acceptsInt());
        self::assertFalse($expectation->acceptsString());
        self::assertFalse($expectation->acceptsFloat());
        self::assertTrue($expectation->acceptsBool());
        self::assertFalse($expectation->acceptsArray());
        // @todo objects
    }

    public function testBoolSuccess(): void
    {
        self::assertSame(true, $this->type->prepareValue(true));
    }

    public function testConvertibleToBoolSuccess(): void
    {
        $input = new class () implements ToBoolConvertibleInterface {
            public function toBool(): bool
            {
                return true;
            }
        };
        self::assertSame(true, $this->type->prepareValue($input));
    }

    public function testConvertibleToBoolWithoutInterfaceFails(): void
    {
        $input = new class () {
            public function toBool(): bool
            {
                return true;
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

    public function testFloatFails(): void
    {
        try {
            $this->type->prepareValue(11.1);
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
            $this->type->prepareValue('true');
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
}
