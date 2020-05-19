<?php declare(strict_types=1);

namespace Slepic\ValueObject\Type;

use Slepic\ValueObject\Type\Downcasting\ToArrayConvertibleInterface;

final class ArrayType implements TypeInterface
{
    private TypeExpectationInterface $expectation;

    public function __construct()
    {
        $this->expectation = new TypeExpectation(
            null,
            false,
            false,
            false,
            false,
            true,
        );
    }

    public function getExpectation(): TypeExpectationInterface
    {
        return $this->expectation;
    }

    public function prepareValue($value)
    {
        if (\is_array($value)) {
            return $value;
        }

        if (\is_object($value) && $value instanceof ToArrayConvertibleInterface) {
            return $value->toArray();
        }

        throw TypeViolation::exception();
    }
}
