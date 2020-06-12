<?php declare(strict_types=1);

namespace Slepic\ValueObject;

trait ImmutableObjectTrait
{
    /**
     * @param string $name
     * @param mixed $value
     * @throws \BadMethodCallException
     */
    final public function __set(string $name, $value): void
    {
        throw new \BadMethodCallException('ImmutableObject cannot set undefined property.');
    }

    /**
     * @param string $name
     * @throws \BadMethodCallException
     */
    final public function __unset(string $name): void
    {
        throw new \BadMethodCallException('ImmutableObject cannot unset property.');
    }
}
