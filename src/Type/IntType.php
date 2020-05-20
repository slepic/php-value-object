<?php declare(strict_types=1);

namespace Slepic\ValueObject\Type;

use Slepic\ValueObject\Type\Downcasting\ToIntConvertibleInterface;

final class IntType implements TypeInterface
{
    private TypeExpectationInterface $expectation;

    public function __construct()
    {
        $this->expectation = new TypeExpectation(
            null,
            false,
            true,
            false,
            false,
            false,
        );
    }

    public function getExpectation(): TypeExpectationInterface
    {
        return $this->expectation;
    }

    public function prepareValue($value): int
    {
        if (\is_int($value)) {
            return $value;
        }

        if (\is_object($value) && $value instanceof ToIntConvertibleInterface) {
            return $value->toInt();
        }

        throw TypeViolation::exception();
    }
}
