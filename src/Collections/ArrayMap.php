<?php declare(strict_types=1);

namespace Slepic\ValueObject\Collections;

use Slepic\ValueObject\Type;
use Slepic\ValueObject\ViolationException;
use Slepic\ValueObject\ViolationExceptionInterface;

abstract class ArrayMap extends ImmutableArrayIterator implements \JsonSerializable
{
    public function __construct(array $value)
    {
        $reflection = new \ReflectionClass(static::class);
        $type = Type::forMethodReturnType($reflection->getMethod('current'));

        $items = [];
        $violations = [];

        foreach ($value as $key => $item) {
            try {
                $items[$key] = $type->prepareValue($item);
            } catch (ViolationExceptionInterface $e) {
                $violations[] = new InvalidPropertyValue(
                    (string) $key,
                    $type->getExpectation(),
                    $value,
                    $e->getViolations()
                );
            }
        }

        if (\count($violations) !== 0) {
            throw new ViolationException($violations);
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
