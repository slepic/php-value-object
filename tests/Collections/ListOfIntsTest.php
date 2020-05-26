<?php declare(strict_types=1);

namespace Slepic\Tests\ValueObject\Collections;

use PHPUnit\Framework\TestCase;
use Slepic\ValueObject\Collections\InvalidListItem;
use Slepic\ValueObject\Collections\ListOfInts;
use Slepic\ValueObject\Type\TypeViolation;
use Slepic\ValueObject\ViolationExceptionInterface;

final class ListOfIntsTest extends TestCase
{
    public function testEmpty(): void
    {
        $list = new ListOfInts([]);
        self::assertSame([], $list->toArray());
    }

    public function testOneElementOk(): void
    {
        $list = new ListOfInts([10]);
        self::assertSame([10], $list->toArray());
    }

    public function testManyElementOk(): void
    {
        $list = new ListOfInts([10, 100, 5]);
        self::assertSame([10, 100, 5], $list->toArray());
    }

    public function testJsonSerialization(): void
    {
        $list = new ListOfInts([10, 100, 5]);
        self::assertSame(\json_encode([10, 100, 5]), \json_encode($list));
    }

    public function testStringElementFails(): void
    {
        try {
            new ListOfInts(['test']);
            self::assertTrue(false, 'Exception not thrown.');
        } catch (ViolationExceptionInterface $e) {
            $violations = $e->getViolations();
            self::assertCount(1, $violations);
            $violation = \reset($violations);
            if ($violation instanceof InvalidListItem) {
                self::assertSame(0, $violation->getKey());
                self::assertSame('test', $violation->getValue());
                $subViolations = $violation->getViolations();
                self::assertCount(1, $subViolations);
                $subViolation = \reset($subViolations);
                self::assertInstanceOf(TypeViolation::class, $subViolation);
            } else {
                self::assertTrue(false, 'Invalid violation type');
            }
        }
    }

    public function testMultipleElementViolations(): void
    {
        try {
            new ListOfInts([1, 2, 'test', 5, 11.1, 10]);
            self::assertTrue(false, 'Exception not thrown.');
        } catch (ViolationExceptionInterface $e) {
            $violations = $e->getViolations();
            self::assertCount(2, $violations);
            $violation = \array_shift($violations);
            if ($violation instanceof InvalidListItem) {
                self::assertSame(2, $violation->getKey());
                self::assertSame('test', $violation->getValue());
                $subViolations = $violation->getViolations();
                self::assertCount(1, $subViolations);
                $subViolation = \reset($subViolations);
                self::assertInstanceOf(TypeViolation::class, $subViolation);
            } else {
                self::assertTrue(false, 'Invalid violation type');
            }
            $violation = \array_shift($violations);
            if ($violation instanceof InvalidListItem) {
                self::assertSame(4, $violation->getKey());
                self::assertSame(11.1, $violation->getValue());
                $subViolations = $violation->getViolations();
                self::assertCount(1, $subViolations);
                $subViolation = \reset($subViolations);
                self::assertInstanceOf(TypeViolation::class, $subViolation);
            } else {
                self::assertTrue(false, 'Invalid violation type');
            }
        }
    }

    public function testAssociativeArrayFails(): void
    {
        try {
            new ListOfInts([1, 2, 3, 'test' => 10, 10, 11]);
            self::assertTrue(false, 'Exception not thrown.');
        } catch (ViolationExceptionInterface $e) {
            $violations = $e->getViolations();
            self::assertCount(1, $violations);
            $violation = \reset($violations);
            self::assertInstanceOf(TypeViolation::class, $violation);
        }
    }
}
