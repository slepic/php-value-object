<?php declare(strict_types=1);

namespace Slepic\ValueObject\Type;

final class NullableTypeExpectation implements TypeExpectationInterface
{
    private TypeExpectationInterface $expectation;

    public function __construct(TypeExpectationInterface $expectation)
    {
        $this->expectation = $expectation;
    }

    public function acceptsNull(): bool
    {
        return true;
    }

    public function acceptsClass(string $class): bool
    {
        return $this->expectation->acceptsClass($class);
    }

    public function acceptsString(): bool
    {
        return $this->expectation->acceptsString();
    }

    public function acceptsInt(): bool
    {
        return $this->expectation->acceptsInt();
    }

    public function acceptsFloat(): bool
    {
        return $this->expectation->acceptsFloat();
    }

    public function acceptsBool(): bool
    {
        return $this->expectation->acceptsBool();
    }

    public function acceptsArray(): bool
    {
        return $this->expectation->acceptsArray();
    }
}
