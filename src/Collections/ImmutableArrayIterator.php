<?php declare(strict_types=1);

namespace Slepic\ValueObject\Collections;

class ImmutableArrayIterator implements \Iterator, \Countable, \ArrayAccess
{
    use ImmutableArrayAccessTrait;

    private \ArrayIterator $items;

    public function __construct(array $items)
    {
        $this->items = new \ArrayIterator($items);
    }

    public function rewind(): void
    {
        $this->items->rewind();
    }

    public function next(): void
    {
        $this->items->next();
    }

    public function valid(): bool
    {
        return $this->items->valid();
    }

    public function key()
    {
        return $this->items->key();
    }

    public function current()
    {
        return $this->items->current();
    }

    public function toArray(): array
    {
        return $this->items->getArrayCopy();
    }

    public function count(): int
    {
        return $this->items->count();
    }

    public function offsetExists($offset): bool
    {
        return $this->items->offsetExists($offset);
    }

    public function offsetGet($offset)
    {
        return $this->items->offsetGet($offset);
    }
}
