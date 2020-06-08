<?php declare(strict_types=1);

namespace Slepic\Tests\ValueObject\Integers;

use PHPUnit\Framework\TestCase;
use Slepic\ValueObject\Integers\BoundedInteger;
use Slepic\ValueObject\Integers\IntegerOutOfBounds;
use Slepic\ValueObject\ViolationExceptionInterface;

final class BoundedIntegerTest extends TestCase
{
    public function testCreateSuccess(): void
    {
        $object = new class (10) extends BoundedInteger {
            protected static function minValue(): int
            {
                return 10;
            }

            protected static function maxValue(): int
            {
                return 10;
            }
        };
        self::assertSame(10, $object->toInt());
    }

    public function testTooBig(): void
    {
        try {
            new class (11) extends BoundedInteger {
                protected static function minValue(): int
                {
                    return 9;
                }

                protected static function maxValue(): int
                {
                    return 10;
                }
            };
            self::assertTrue(false, 'Exception not thrown');
        } catch (ViolationExceptionInterface $e) {
            $violations = $e->getViolations();
            self::assertCount(1, $violations);
            $violation = \reset($violations);
            if ($violation instanceof IntegerOutOfBounds) {
                self::assertSame(9, $violation->getMinValue());
                self::assertSame(10, $violation->getMaxValue());
            } else {
                self::assertTrue(false, 'Bad violation ' . \get_class($violation));
            }
        }
    }

    public function testTooSmall(): void
    {
        try {
            new class (8) extends BoundedInteger {
                protected static function minValue(): int
                {
                    return 9;
                }

                protected static function maxValue(): int
                {
                    return 10;
                }
            };
            self::assertTrue(false, 'Exception not thrown');
        } catch (ViolationExceptionInterface $e) {
            $violations = $e->getViolations();
            self::assertCount(1, $violations);
            $violation = \reset($violations);
            if ($violation instanceof IntegerOutOfBounds) {
                self::assertSame(9, $violation->getMinValue());
                self::assertSame(10, $violation->getMaxValue());
            } else {
                self::assertTrue(false, 'Bad violation ' . \get_class($violation));
            }
        }
    }
}
