<?php declare(strict_types=1);

namespace Slepic\ValueObject\Collections;

use Slepic\ValueObject\ImmutableObjectTrait;

trait ImmutableArrayAccessTrait
{
    use ImmutableObjectTrait;

    /**
     * @param array-key $offset
     * @param mixed $value
     * @throws \BadMethodCallException
     */
    final public function offsetSet($offset, $value): void
    {
        throw new \BadMethodCallException('ImmutableArrayAccess cannot set offset.');
    }

    /**
     * @param array-key $offset
     * @throws \BadMethodCallException
     */
    final public function offsetUnset($offset): void
    {
        throw new \BadMethodCallException('ImmutableArrayAccess cannot unset offset.');
    }
}
