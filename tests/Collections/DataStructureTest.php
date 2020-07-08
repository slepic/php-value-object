<?php declare(strict_types=1);

namespace Slepic\Tests\ValueObject\Collections;

use PHPUnit\Framework\TestCase;
use Slepic\ValueObject\Collections\CollectionViolation;
use Slepic\ValueObject\Type\TypeViolation;
use Slepic\ValueObject\ViolationExceptionInterface;

final class DataStructureTest extends TestCase
{
    public function testThatCanCreateFromArray(): void
    {
        $structure = DataStructureFixture::fromArray([
            'x' => 10,
            'y' => 11.1,
            'z' => 'value',
        ]);

        self::assertInstanceOf(DataStructureFixture::class, $structure);
        self::assertSame(10, $structure->x);
        self::assertSame(11.1, $structure->getY());
        self::assertSame('value', $structure->getZ());
    }

    public function testThatCanModifyAll(): void
    {
        $input = new DataStructureFixture(1, 11.1, 'value');
        $structure = $input->with([
            'x' => 10,
        ]);

        self::assertSame(10, $structure->x);
        self::assertSame(11.1, $structure->getY());
        self::assertSame('value', $structure->getZ());
    }

    public function testThatAllViolationsAreThrown(): void
    {
        try {
            DataStructureFixture::fromArray([
                'x' => 10,
                'y' => 10,
                'extra' => 'null',
            ]);
            self::assertTrue(false, 'Exception not thrown.');
        } catch (ViolationExceptionInterface $e) {
            $violations = $e->getViolations();
            self::assertCount(3, $violations);

            $violation = \array_shift($violations);
            if ($violation instanceof CollectionViolation) {
                self::assertSame('y', $violation->getKey());
                self::assertSame(10, $violation->getValue());
                $subViolations = $violation->getViolations();
                self::assertCount(1, $subViolations);
                $subViolation = \reset($subViolations);
                self::assertInstanceOf(TypeViolation::class, $subViolation);
            } else {
                self::assertTrue(false, 'Bad violation type.');
            }

            $violation = \array_shift($violations);
            if ($violation instanceof CollectionViolation) {
                self::assertSame('z', $violation->getKey());
            } else {
                self::assertTrue(false, 'Bad violation type.');
            }

            $violation = \array_shift($violations);
            if ($violation instanceof CollectionViolation) {
                self::assertSame('extra', $violation->getKey());
                self::assertSame('null', $violation->getValue());
            } else {
                self::assertTrue(false, 'Bad violation type.');
            }
        }
    }
}
