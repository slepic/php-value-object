<?php declare(strict_types=1);

namespace Slepic\ValueObject\Type;

use Slepic\ValueObject\Type\Downcasting\ToBoolConvertibleInterface;

final class BoolType implements TypeInterface
{
    private TypeExpectationInterface $expectation;

    public function __construct()
    {
        $this->expectation = new TypeExpectation(
            null,
            false,
            false,
            false,
            true,
            false,
        );
    }

    public function getExpectation(): TypeExpectationInterface
    {
        return $this->expectation;
    }

    public function prepareValue($value)
    {
        if (\is_bool($value)) {
            return $value;
        }

        if (\is_object($value) && $value instanceof ToBoolConvertibleInterface) {
            return $value->toBool();
        }

        throw TypeViolation::exception();
    }
}
