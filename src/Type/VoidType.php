<?php declare(strict_types=1);

namespace Slepic\ValueObject\Type;

final class VoidType implements TypeInterface
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
            false
        );
    }

    public function getExpectation(): TypeExpectationInterface
    {
        return $this->expectation;
    }

    public function prepareValue($value)
    {
        throw TypeViolation::exception();
    }
}
