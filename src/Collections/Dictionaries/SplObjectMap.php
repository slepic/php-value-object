<?php declare(strict_types=1);

namespace Slepic\ValueObject\Collections\Dictionaries;

use Slepic\ValueObject\Collections\CollectionException;
use Slepic\ValueObject\Collections\ImmutableArrayIterator;
use Slepic\ValueObject\Collections\KeyValuePair;
use Slepic\ValueObject\Collections\KeyValuePairInterface;
use Slepic\ValueObject\InvalidValueExceptionInterface;
use Slepic\ValueObject\ValueObject;

/**
 * @psalm-template TKey of object
 * @psalm-template TValue
 */
abstract class SplObjectMap extends ImmutableArrayIterator implements \JsonSerializable
{
    private \SplObjectStorage $storage;

    /**
     * @param array $value
     */
    public function __construct(array $value)
    {
        $keyFilter = ValueObject::factoryForMethodReturnType(static::class, 'currentKey');
        $itemFilter = ValueObject::factoryForMethodReturnType(static::class, 'currentValue');

        $storage = new \SplObjectStorage();
        $raw = [];
        $errors = [];

        foreach ($value as $rawKey => $item) {
            try {
                $key = $keyFilter($rawKey);
            } catch (InvalidValueExceptionInterface $e) {
                $errors[$rawKey] = $e;
                continue;
            }

            try {
                $item = $itemFilter($item);
            } catch (InvalidValueExceptionInterface $e) {
                $errors[$rawKey] = $e;
                continue;
            }

            $storage->attach($key, $item);
            $raw[$rawKey] = $key;
        }

        if (\count($errors) !== 0) {
            throw new CollectionException($errors, $value);
        }

        $this->storage = $storage;
        parent::__construct($raw);
    }

    public function rewind(): void
    {
        parent::rewind();
        $this->storage->rewind();
    }

    public function next(): void
    {
        parent::next();
        $this->storage->next();
    }

    public function key(): int
    {
        return $this->storage->key();
    }

    /**
     * @psalm-return KeyValuePairInterface<TKey, TValue>
     *
     * @return KeyValuePairInterface
     */
    public function current(): KeyValuePairInterface
    {
        return new KeyValuePair($this->currentKey(), $this->currentValue());
    }

    /**
     * @return object
     */
    public function currentKey()
    {
        return $this->storage->current();
    }

    /**
     * @psalm-return TValue
     *
     * @return mixed
     */
    public function currentValue()
    {
        return $this->storage->getInfo();
    }

    public function toArray(): array
    {
        $result = [];
        foreach (parent::toArray() as $rawKey => $key) {
            $result[$rawKey] = $this->storage[$key];
        }
        return $result;
    }

    public function jsonSerialize(): \stdClass
    {
        return (object) $this->toArray();
    }

    public function offsetExists($offset): bool
    {
        if (\is_object($offset)) {
            return $this->storage->offsetExists($offset);
        }
        return parent::offsetExists($offset);
    }

    public function offsetGet($offset)
    {
        if (\is_object($offset)) {
            return $this->storage->offsetGet($offset);
        }
        return parent::offsetGet($offset);
    }
}
