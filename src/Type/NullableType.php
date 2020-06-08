<?php declare(strict_types=1);

namespace Slepic\ValueObject\Type;

final class NullableType implements TypeInterface
{
    private TypeInterface $type;

    public function __construct(TypeInterface $type)
    {
        $this->type = $type;
    }

    public function getExpectation(): TypeExpectationInterface
    {
        return new NullableTypeExpectation($this->type->getExpectation());
    }

    public function prepareValue($value)
    {
        if ($value === null) {
            return null;
        }

        return $this->type->prepareValue($value);
    }
}
