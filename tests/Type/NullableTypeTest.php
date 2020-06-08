<?php declare(strict_types=1);

namespace Slepic\Tests\ValueObject\Type;

use PHPUnit\Framework\TestCase;
use Slepic\ValueObject\Type\NullableType;
use Slepic\ValueObject\Type\NullableTypeExpectation;
use Slepic\ValueObject\Type\TypeExpectationInterface;
use Slepic\ValueObject\Type\TypeInterface;
use Slepic\ValueObject\Type\TypeViolation;
use Slepic\ValueObject\ViolationExceptionInterface;

class NullableTypeTest extends TestCase
{
    private NullableType $type;
    private TypeInterface $innerType;

    protected function setUp(): void
    {
        parent::setUp();
        $this->innerType = self::createMock(TypeInterface::class);
        $this->type = new NullableType($this->innerType);
    }

    public function testCreatesNullableExpectation(): void
    {
        $expectation = self::createMock(TypeExpectationInterface::class);
        $this->innerType->expects(self::once())
            ->method('getExpectation')
            ->willReturn($expectation);
        self::assertInstanceOf(NullableTypeExpectation::class, $this->type->getExpectation());
    }

    public function testAcceptsNull(): void
    {
        $this->innerType->expects(self::never())->method('getExpectation');
        $this->innerType->expects(self::never())->method('prepareValue');
        self::assertNull($this->type->prepareValue(null));
    }

    public function testAcceptsBool(): void
    {
        $input = true;
        $this->innerType->expects(self::once())->method('prepareValue')
            ->with($input)
            ->willReturn($input);
        self::assertSame($input, $this->type->prepareValue($input));
    }

    public function testAcceptsInt(): void
    {
        $input = 10;
        $this->innerType->expects(self::once())->method('prepareValue')
            ->with($input)
            ->willReturn($input);
        self::assertSame($input, $this->type->prepareValue($input));
    }

    public function testAcceptsFloat(): void
    {
        $input = 11.1;
        $this->innerType->expects(self::once())->method('prepareValue')
            ->with($input)
            ->willReturn($input);
        self::assertSame($input, $this->type->prepareValue($input));
    }

    public function testAcceptsString(): void
    {
        $input = 'test';
        $this->innerType->expects(self::once())->method('prepareValue')
            ->with($input)
            ->willReturn($input);
        self::assertSame($input, $this->type->prepareValue($input));
    }

    public function testAcceptsArray(): void
    {
        $input = ['value'];
        $this->innerType->expects(self::once())->method('prepareValue')
            ->with($input)
            ->willReturn($input);
        self::assertSame($input, $this->type->prepareValue($input));
    }

    public function testAcceptsObject(): void
    {
        $input = (object) ['test' => 'value'];
        $this->innerType->expects(self::once())->method('prepareValue')
            ->with($input)
            ->willReturn($input);
        self::assertSame($input, $this->type->prepareValue($input));
    }

    public function testNotAcceptsString(): void
    {
        $input = 'test';
        $e = TypeViolation::exception();
        $this->innerType->method('prepareValue')
            ->with($input)
            ->willThrowException($e);

        try {
            $this->type->prepareValue($input);
            self::assertTrue(false, 'Not thrown.');
        } catch (ViolationExceptionInterface $caught) {
            self::assertSame($e, $caught);
        }
    }
}
