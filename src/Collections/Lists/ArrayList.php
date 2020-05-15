<?php declare(strict_types=1);

namespace Slepic\ValueObject\Collections\Lists;

use Slepic\ValueObject\Collections\CollectionException;
use Slepic\ValueObject\Collections\ImmutableArrayIterator;
use Slepic\ValueObject\InvalidTypeException;
use Slepic\ValueObject\InvalidValueExceptionInterface;
use Slepic\ValueObject\ValueObject;

/**
 * Represents an array value object with keys being consecutive integers starting at 0.
 *
 * All values must have the same (super) type.
 */
abstract class ArrayList extends ImmutableArrayIterator implements \JsonSerializable
{
    public function __construct(array $value)
    {
        $filter = ValueObject::factoryForMethodReturnType(static::class, 'current');

        $index = 0;
        $items = [];
        $errors = [];
        foreach ($value as $key => $item) {
            if ($key !== $index) {
                throw new InvalidTypeException($value, 'array');
            }

            try {
                $items[] = $filter($item);
            } catch (InvalidValueExceptionInterface $e) {
                $errors[$index] = $e;
            }

            ++$index;
        }

        if (\count($errors) > 0) {
            throw new CollectionException($errors, $value);
        }

        parent::__construct($items);
    }

    public function key(): int
    {
        return parent::key();
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
