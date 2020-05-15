<?php declare(strict_types=1);

namespace Slepic\ValueObject;

final class TypeExpectation implements TypeExpectationInterface
{
    private string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function is(string $class): bool
    {
        return $this->name === $class || \is_a($this->name, $class, true);
    }

    public function isString(): bool
    {
        return $this->name === 'string';
    }

    public function isInt(): bool
    {
        return $this->name === 'int';
    }

    public function isFloat(): bool
    {
        return $this->name === 'float';
    }

    public function isArray(): bool
    {
        return $this->name === 'array';
    }

    public function isObject(): bool
    {
        return false;
    }
}
