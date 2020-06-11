<?php declare(strict_types=1);

namespace Slepic\Tests\ValueObject\Collections;

use PHPUnit\Framework\TestCase;
use Slepic\ValueObject\Collections\FromArrayConstructor;
use Slepic\ValueObject\Collections\InvalidPropertyValue;
use Slepic\ValueObject\Collections\MissingRequiredProperty;
use Slepic\ValueObject\Collections\UnknownProperty;
use Slepic\ValueObject\DateTime\DateTimeValue;
use Slepic\ValueObject\Integers\IntegerValue;
use Slepic\ValueObject\Type\TypeViolation;
use Slepic\ValueObject\ViolationExceptionInterface;

final class FromArrayConstructorTest extends TestCase
{
    public function testThatCanCreateFromMatchingTypes(): void
    {
        $dummy = new class ('x', 1) {
            public string $x;
            public int $y;

            public function __construct(string $x, int $y)
            {
                $this->x = $x;
                $this->y = $y;
            }
        };

        $class = \get_class($dummy);

        $output = FromArrayConstructor::constructFromArray($class, [
            'x' => 'value',
            'y' => 10,
        ]);

        self::assertInstanceOf($class, $output);
        self::assertSame('value', $output->x);
        self::assertSame(10, $output->y);
    }

    public function testThatDefaultsAreRespected(): void
    {
        $dummy = new class ('x', 1) {
            public ?string $x;
            public int $y;

            public function __construct(?string $x = null, int $y = 10)
            {
                $this->x = $x;
                $this->y = $y;
            }
        };

        $class = \get_class($dummy);

        $output = FromArrayConstructor::constructFromArray($class, []);

        self::assertInstanceOf($class, $output);
        self::assertSame(null, $output->x);
        self::assertSame(10, $output->y);
    }

    public function testThatCanCreateWithUpcastingAndDowncasting(): void
    {
        $dummy = new class (DateTimeValue::fromString('2020-06-20T00:15:16+00:00'), 1) {
            public DateTimeValue $x;
            public int $y;

            public function __construct(DateTimeValue $x, int $y)
            {
                $this->x = $x;
                $this->y = $y;
            }
        };

        $class = \get_class($dummy);

        $output = FromArrayConstructor::constructFromArray($class, [
            'x' => new \DateTimeImmutable('2020-06-15T05:04:03+00:00'),
            'y' => new IntegerValue(11),
        ]);

        self::assertInstanceOf($class, $output);
        self::assertSame('2020-06-15T05:04:03+00:00', (string) $output->x);
        self::assertSame(11, $output->y);
    }

    public function testThatAllViolationsAreReported(): void
    {
        $dummy = new class ('x', 1) {
            public string $x;
            public int $y;

            public function __construct(string $x, int $y)
            {
                $this->x = $x;
                $this->y = $y;
            }
        };

        $class = \get_class($dummy);

        try {
            FromArrayConstructor::constructFromArray($class, [
                'x' => 10,
                'z' => 'extra',
            ]);
            self::assertTrue(false, 'Exception not thrown.');
        } catch (ViolationExceptionInterface $e) {
            $violations = $e->getViolations();
            self::assertCount(3, $violations);

            $violation = \array_shift($violations);
            if ($violation instanceof InvalidPropertyValue) {
                self::assertSame('x', $violation->getKey());
                self::assertSame(10, $violation->getValue());
                $subViolations = $violation->getViolations();
                self::assertCount(1, $subViolations);
                $subViolation = \reset($subViolations);
                self::assertInstanceOf(TypeViolation::class, $subViolation);
            } else {
                self::assertTrue(false, 'Unexpected violation type.');
            }

            $violation = \array_shift($violations);
            if ($violation instanceof MissingRequiredProperty) {
                self::assertSame('y', $violation->getKey());
            } else {
                self::assertTrue(false, 'Unexpected violation type.');
            }

            $violation = \array_shift($violations);
            if ($violation instanceof UnknownProperty) {
                self::assertSame('z', $violation->getKey());
                self::assertSame('extra', $violation->getValue());
            } else {
                self::assertTrue(false, 'Unexpected violation type.');
            }
        }
    }
}