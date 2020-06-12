<?php declare(strict_types=1);

namespace Slepic\Tests\ValueObject\Collections;

use PHPUnit\Framework\TestCase;
use Slepic\ValueObject\Collections\ArrayMap;
use Slepic\ValueObject\Collections\InvalidPropertyValue;
use Slepic\ValueObject\Integers\IntegerValue;
use Slepic\ValueObject\Type\TypeViolation;
use Slepic\ValueObject\ViolationExceptionInterface;

final class ArrayMapTest extends TestCase
{
    public function testThatCannotConstructWithoutCurrentReturnTypeHint(): void
    {
        try {
            new class ([]) extends ArrayMap {

            };
            self::assertTrue(false, 'Exception not thrown.');
        } catch (\Throwable $e) {
            self::assertNotInstanceOf(ViolationExceptionInterface::class, $e);
        }
    }

    public function testThatCanConstructFromEmptyArray(): void
    {
        $map = new class ([]) extends ArrayMap {
            public function current(): int
            {
                return parent::current();
            }
        };

        self::assertSame([], $map->toArray());
    }

    public function testThatCanConstructFromSameValueTypeArray(): void
    {
        $input = ['a' => 1, 'b' => 2, 'c' => 3];
        $map = new class ($input) extends ArrayMap {
            public function current(): int
            {
                return parent::current();
            }
        };

        self::assertSame($input, $map->toArray());
    }

    public function testThatCanConstructFromUsingDowncasting(): void
    {
        $input = [
            'a' => new IntegerValue(1),
            'b' => new IntegerValue(2),
            'c' => new IntegerValue(3),
        ];
        $map = new class ($input) extends ArrayMap {
            public function current(): int
            {
                return parent::current();
            }
        };

        self::assertEquals(['a' => 1, 'b' => 2, 'c' => 3], $map->toArray());
    }

    public function testThatCannotCreateWithInvalidItems(): void
    {
        try {
            new class (['a' => 1, 'b' => 2.0, 'c' => '3']) extends ArrayMap {
                public function current(): int
                {
                    return parent::current();
                }
            };
        } catch (ViolationExceptionInterface $e) {
            $violations = $e->getViolations();
            self::assertCount(2, $violations);

            $violation = \array_shift($violations);
            if ($violation instanceof InvalidPropertyValue) {
                self::assertSame('b', $violation->getKey());
                self::assertSame(2.0, $violation->getValue());
                $subViolations = $violation->getViolations();
                self::assertCount(1, $subViolations);
                $subViolation = \reset($subViolations);
                self::assertInstanceOf(TypeViolation::class, $subViolation);
            } else {
                self::assertTrue(false, 'Bad violation type.');
            }

            $violation = \array_shift($violations);
            if ($violation instanceof InvalidPropertyValue) {
                self::assertSame('c', $violation->getKey());
                self::assertSame('3', $violation->getValue());
                $subViolations = $violation->getViolations();
                self::assertCount(1, $subViolations);
                $subViolation = \reset($subViolations);
                self::assertInstanceOf(TypeViolation::class, $subViolation);
            } else {
                self::assertTrue(false, 'Bad violation type.');
            }
        }
    }
}
