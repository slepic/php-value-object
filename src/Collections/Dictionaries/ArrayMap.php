<?php declare(strict_types=1);

namespace Slepic\ValueObject\Collections\Dictionaries;

use Slepic\ValueObject\Collections\CollectionException;
use Slepic\ValueObject\Collections\ImmutableArrayIterator;
use Slepic\ValueObject\InvalidValueExceptionInterface;
use Slepic\ValueObject\ValueObject;

abstract class ArrayMap extends ImmutableArrayIterator implements \JsonSerializable
{
    public function __construct(array $value)
    {
        $filter = ValueObject::factoryForMethodReturnType(static::class, 'current');
        $items = [];
        $errors = [];

        foreach ($value as $key => $item) {
            try {
                $items[$key] = $filter($item);
            } catch (InvalidValueExceptionInterface $e) {
                echo "caught: " . \get_class($e) . "\n";
                echo $e->getMessage() . "\n";
                $errors[$key] = $e;
            }
        }

        if (\count($errors) !== 0) {
            throw new CollectionException($errors, $value);
        }

        parent::__construct($items);
    }

    public function key(): string
    {
        return (string) parent::key();
    }

    public function jsonSerialize(): \stdClass
    {
        return (object) $this->toArray();
    }
}
