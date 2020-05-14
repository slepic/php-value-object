<?php declare(strict_types=1);

namespace Slepic\ValueObject\Collections\Lists;

use Slepic\ValueObject\Collections\CollectionException;
use Slepic\ValueObject\InvalidTypeException;
use Slepic\ValueObject\InvalidValueExceptionInterface;
use Slepic\ValueObject\ValueObject;

/**
 * Represents an array value object with keys being consecutive integers starting at 0.
 *
 * All values must have the same (super)type.
 *
 * @todo
 */
abstract class ArrayList implements \Iterator, \JsonSerializable
{
    private \ArrayIterator $items;

    public function __construct(array $value)
    {
        $filter = static::getItemFilter();

        $index = 0;
        $items = new \ArrayIterator();
        $errors = [];
        foreach ($value as $key => $item) {
            if ($key !== $index) {
                throw new InvalidTypeException($value, 'array');
            }

            try {
                $items->append($filter($item));
            } catch (InvalidValueExceptionInterface $e) {
                $errors[$index] = $e;
            }

            ++$index;
        }

        if (\count($errors) > 0) {
            throw new CollectionException($errors, $value);
        }

        $this->items = $items;
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

    public function key(): int
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

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    private static function getItemFilter(): callable
    {
        $method = new \ReflectionMethod(static::class, 'current');
        $targetType = $method->getReturnType();
        if ($targetType === null) {
            throw new \UnexpectedValueException('Method current does not have a return type.');
        }

        if (!$targetType instanceof \ReflectionNamedType) {
            throw new \RuntimeException('ReflectionNamedType not supported.');
        }

        /**
         * @psalm-suppress MissingClosureReturnType
         * @psalm-suppress MissingClosureParamType
         */
        return fn ($value) => ValueObject::prepare($targetType, $value);
    }
}
