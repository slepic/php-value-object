<?php declare(strict_types=1);

namespace Slepic\Tests\ValueObject\Collections;

use PHPUnit\Framework\TestCase;
use Slepic\ValueObject\Collections\NestedViolation;
use Slepic\ValueObject\Collections\DataTransferObject;
use Slepic\ValueObject\Collections\ListOfInts;
use Slepic\ValueObject\Type\TypeViolation;
use Slepic\ValueObject\ViolationExceptionInterface;

class DataTransferObjectTest extends TestCase
{
    public function testIsAbstract(): void
    {
        $reflection = new \ReflectionClass(DataTransferObject::class);
        self::assertTrue($reflection->isAbstract());
    }

    public function testThatIntIsCopiedToIntPropertyWithoutDefault(): void
    {
        $input = ['xyz' => 10];
        $dto = new class($input) extends DataTransferObject {
            public int $xyz;
        };
        self::assertSame(10, $dto->xyz);
    }

    public function testThatIntIsCopiedToIntPropertyWithDefault(): void
    {
        $input = ['xyz' => 10];
        $dto = new class($input) extends DataTransferObject {
            public int $xyz = 5;
        };
        self::assertSame(10, $dto->xyz);
    }

    public function testThatIntIsCopiedToNullableIntPropertyWithoutDefault(): void
    {
        $input = ['xyz' => 10];
        $dto = new class($input) extends DataTransferObject {
            public ?int $xyz;
        };
        self::assertSame(10, $dto->xyz);
    }

    public function testThatNullIsCopiedToNullableIntPropertyWithoutDefault(): void
    {
        $input = ['xyz' => null];
        $dto = new class($input) extends DataTransferObject {
            public ?int $xyz;
        };
        self::assertSame(null, $dto->xyz);
    }

    public function testThatNullIsCopiedToNullableIntPropertyWithDefault(): void
    {
        $input = ['xyz' => null];
        $dto = new class($input) extends DataTransferObject {
            public ?int $xyz = 10;
        };
        self::assertSame(null, $dto->xyz);
    }

    public function testThatMissingRequiredPropertyThrows(): void
    {
        $input = [];
        try {
            new class($input) extends DataTransferObject {
                public int $xyz;
            };
            self::assertTrue(false, 'Exception not thrown.');
        } catch (ViolationExceptionInterface $e) {
            $violations = $e->getViolations();
            self::assertCount(1, $violations);
            $violation = \reset($violations);
            if ($violation instanceof NestedViolation) {
                self::assertSame('xyz', $violation->getKey());
            } else {
                self::assertTrue(false, 'Violation has incorrect type.');
            }
        }
    }

    public function testThatNonValueObjectCanBeCopied(): void
    {
        $value = new \DateTimeImmutable();
        $input = [
            'xyz' => $value,
        ];
        $dto = new class($input) extends DataTransferObject {
            public \DateTimeImmutable $xyz;
        };

        self::assertSame($value, $dto->xyz);
    }

    public function testThatNonValueObjectCannotBeConstructedFromPrimitive(): void
    {
        $input = [
            'xyz' => \date(\DATE_ATOM),
        ];
        try {
            new class($input) extends DataTransferObject {
                public \DateTimeImmutable $xyz;
            };
            self::assertTrue(false, 'Exception not thrown.');
        } catch (ViolationExceptionInterface $e) {
            $violations = $e->getViolations();
            self::assertCount(1, $violations);
            $violation = \reset($violations);
            if ($violation instanceof NestedViolation) {
                self::assertSame('xyz', $violation->getKey());
                self::assertSame($input['xyz'], $violation->getValue());
                $subViolations = $violation->getViolations();
                self::assertCount(1, $subViolations);
                $subViolation = \reset($subViolations);
                self::assertInstanceOf(TypeViolation::class, $subViolation);
            } else {
                self::assertTrue(false, 'Invalid violation type.');
            }
        }
    }

    public function testThatValueObjectCanBeCreatedFromPrimitive(): void
    {
        $input = [
           'xyz' => [1, 2, 3],
        ];
        $dto = new class($input) extends DataTransferObject {
            public ListOfInts $xyz;
        };
        self::assertSame([1, 2, 3], $dto->xyz->toArray());
    }

    public function testThatValueObjectPropertyWithoutDefaultDoesNotAcceptNull(): void
    {
        $input = [
            'xyz' => null,
        ];
        try {
            new class($input) extends DataTransferObject {
                public ListOfInts $xyz;
            };
            self::assertTrue(false, 'Exception not thrown.');
        } catch (ViolationExceptionInterface $e) {
            $violations = $e->getViolations();
            self::assertCount(1, $violations);
            $violation = \reset($violations);
            if ($violation instanceof NestedViolation) {
                self::assertSame('xyz', $violation->getKey());
                self::assertSame(null, $violation->getValue());
                $subViolations = $violation->getViolations();
                self::assertCount(1, $subViolations);
                $subViolation = \reset($subViolations);
                self::assertInstanceOf(TypeViolation::class, $subViolation);
            } else {
                self::assertTrue(false, 'Invalid violation type');
            }
        }
    }

    public function testThatMultipleViolationsCanBeCreated(): void
    {
        $input = [
            'int' => 'string',
            'string' => 10,
        ];

        try {
            new class($input) extends DataTransferObject {
                public int $int;
                public string $string;
            };
            self::assertTrue(false, 'Exception not thrown.');
        } catch (ViolationExceptionInterface $e) {
            $violations = $e->getViolations();
            self::assertCount(2, $violations);
            $violation = \array_shift($violations);
            if ($violation instanceof NestedViolation) {
                self::assertSame('int', $violation->getKey());
                self::assertSame('string', $violation->getValue());
                $subViolations = $violation->getViolations();
                self::assertCount(1, $subViolations);
                $subViolation = \reset($subViolations);
                self::assertInstanceOf(TypeViolation::class, $subViolation);
            } else {
                self::assertTrue(false, 'Invalid violation type');
            }
            $violation = \array_shift($violations);
            if ($violation instanceof NestedViolation) {
                self::assertSame('string', $violation->getKey());
                self::assertSame(10, $violation->getValue());
                $subViolations = $violation->getViolations();
                self::assertCount(1, $subViolations);
                $subViolation = \reset($subViolations);
                self::assertInstanceOf(TypeViolation::class, $subViolation);
            } else {
                self::assertTrue(false, 'Invalid violation type');
            }
        }
    }

    public function testThatUnknownPropertiesAreViolationsByDefault(): void
    {
        $input = [
            'extra' => 'value',
        ];

        try {
            new class ($input) extends DataTransferObject {
                public ?int $id = null;
            };
            self::assertTrue(false, 'Exception not thrown.');
        } catch (ViolationExceptionInterface $e) {
            $violations = $e->getViolations();
            self::assertCount(1, $violations);
            $violation = \reset($violations);
            if ($violation instanceof NestedViolation) {
                self::assertSame('extra', $violation->getKey());
                self::assertSame('value', $violation->getValue());
            } else {
                self::assertTrue(false, 'Bad violation type.');
            }
        }
    }

    public function testThatUnknownPropertiesAreIgnoredWhenOverridenConstantFlag(): void
    {
        $input = [
            'id' => 5,
            'extra' => 'value',
        ];

        $dto = new class ($input) extends DataTransferObject {
            protected const IGNORE_UNKNOWN_PROPERTIES = true;
            public ?int $id = null;
        };

        self::assertSame(5, $dto->id);
    }
}
