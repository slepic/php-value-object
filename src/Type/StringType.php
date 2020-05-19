<?php declare(strict_types=1);

namespace Slepic\ValueObject\Type;

final class StringType implements TypeInterface
{
    private TypeExpectationInterface $expectation;

    public function __construct()
    {
        $this->expectation = new TypeExpectation(
            null,
            true,
            false,
            false,
            false,
            false,
        );
    }

    public function getExpectation(): TypeExpectationInterface
    {
        return $this->expectation;
    }

    public function prepareValue($value)
    {
        if (\is_string($value)) {
            return $value;
        }

        if (\is_object($value) && \method_exists($value, '__toString')) {
            return (string) $value;
        }

        throw TypeViolation::exception();
    }
}
