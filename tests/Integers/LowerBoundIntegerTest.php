<?php declare(strict_types=1);

namespace Slepic\Tests\ValueObject\Integers;

use PHPUnit\Framework\TestCase;
use Slepic\ValueObject\Integers\IntegerTooSmall;
use Slepic\ValueObject\Integers\LowerBoundInteger;
use Slepic\ValueObject\ViolationExceptionInterface;

final class LowerBoundIntegerTest extends TestCase
{
    public function testAboveBoundSucceeds(): void
    {
        $value = new class (10) extends LowerBoundInteger {
            final protected static function minValue(): int
            {
                return 9;
            }
        };
        self::assertSame(10, $value->toInt());
    }

    public function testAtBoundSucceeds(): void
    {
        $value = new class (10) extends LowerBoundInteger {
            final protected static function minValue(): int
            {
                return 10;
            }
        };
        self::assertSame(10, $value->toInt());
    }

    public function testBelowBoundFails(): void
    {
        try {
            new class (9) extends LowerBoundInteger {
                final protected static function minValue(): int
                {
                    return 10;
                }
            };
            self::assertTrue(false, 'Exception not thrown.');
        } catch (ViolationExceptionInterface $e) {
            $violations = $e->getViolations();
            self::assertCount(1, $violations);
            $violation = \reset($violations);
            if ($violation instanceof IntegerTooSmall) {
                self::assertSame(10, $violation->getLowerBound());
            } else {
                self::assertTrue(false, 'Bad violation type.');
            }
        }
    }
}
