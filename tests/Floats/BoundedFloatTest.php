<?php declare(strict_types=1);

namespace Slepic\Tests\ValueObject\Floats;

use PHPUnit\Framework\TestCase;
use Slepic\ValueObject\Floats\BoundedFloat;
use Slepic\ValueObject\Floats\FloatOutOfBounds;
use Slepic\ValueObject\ViolationExceptionInterface;

final class BoundedFloatTest extends TestCase
{
    public function testCreateSuccess(): void
    {
        $object = new class (11.1) extends BoundedFloat {
            protected static function minValue(): float
            {
                return 11.1;
            }

            protected static function maxValue(): float
            {
                return 11.1;
            }
        };
        self::assertSame(11.1, $object->toFloat());
    }

    public function testTooBig(): void
    {
        try {
            new class (11.1) extends BoundedFloat {
                protected static function minValue(): float
                {
                    return 9.0;
                }

                protected static function maxValue(): float
                {
                    return 11.09;
                }
            };
            self::assertTrue(false, 'Exception not thrown');
        } catch (ViolationExceptioninterface $e) {
            $violations = $e->getViolations();
            self::assertCount(1, $violations);
            $violation = \reset($violations);
            if ($violation instanceof FloatOutOfBounds) {
                self::assertSame(9.0, $violation->getMinValue());
                self::assertSame(11.09, $violation->getMaxValue());
            } else {
                self::assertTrue(false, 'Bad violation ' . \get_class($violation));
            }
        }
    }

    public function testTooSmall(): void
    {
        try {
            new class (8.9) extends BoundedFloat {
                protected static function minValue(): float
                {
                    return 9.0;
                }

                protected static function maxValue(): float
                {
                    return 11.1;
                }
            };
            self::assertTrue(false, 'Exception not thrown');
        } catch (ViolationExceptioninterface $e) {
            $violations = $e->getViolations();
            self::assertCount(1, $violations);
            $violation = \reset($violations);
            if ($violation instanceof FloatOutOfBounds) {
                self::assertSame(9.0, $violation->getMinValue());
                self::assertSame(11.1, $violation->getMaxValue());
            } else {
                self::assertTrue(false, 'Bad violation ' . \get_class($violation));
            }
        }
    }
}
