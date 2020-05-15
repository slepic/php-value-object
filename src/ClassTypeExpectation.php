<?php declare(strict_types=1);

namespace Slepic\ValueObject;

final class ClassTypeExpectation implements TypeExpectationInterface
{
    private \ReflectionClass $reflection;

    public function __construct(\ReflectionClass $reflection)
    {
        $this->reflection = $reflection;
    }

    public function getName(): string
    {
        return $this->reflection->getName();
    }

    public function isString(): bool
    {
        return false;
    }

    public function isInt(): bool
    {
        return false;
    }

    public function isFloat(): bool
    {
        return false;
    }

    public function isArray(): bool
    {
        return false;
    }

    public function isObject(): bool
    {
        return true;
    }

    public function is(string $class): bool
    {
        if ($class === $this->getName()) {
            return true;
        }
        return $this->reflection->isSubclassOf($class);
    }
}
