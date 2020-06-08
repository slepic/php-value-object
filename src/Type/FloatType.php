<?php declare(strict_types=1);

namespace Slepic\ValueObject\Type;

use Slepic\ValueObject\Type\Downcasting\ToFloatConvertibleInterface;

final class FloatType implements TypeInterface
{
    private TypeExpectationInterface $expectation;

    public function __construct()
    {
        $this->expectation = new TypeExpectation(
            null,
            false,
            false,
            true,
            false,
            false,
        );
    }

    public function getExpectation(): TypeExpectationInterface
    {
        return $this->expectation;
    }

    public function prepareValue($value): float
    {
        if (\is_float($value)) {
            return $value;
        }

        if (\is_object($value) && $value instanceof ToFloatConvertibleInterface) {
            return $value->toFloat();
        }

        throw TypeViolation::exception();
    }
}
