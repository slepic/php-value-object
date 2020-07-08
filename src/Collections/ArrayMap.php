<?php declare(strict_types=1);

namespace Slepic\ValueObject\Collections;

use Slepic\ValueObject\Type;
use Slepic\ValueObject\ViolationException;
use Slepic\ValueObject\ViolationExceptionInterface;

/**
 * @template TValue
 * @template-extends ImmutableArrayIterator<string, TValue>
 */
abstract class ArrayMap extends ImmutableArrayIterator implements \JsonSerializable
{
    /**
     * @psalm-param array<string, TValue> $input
     * @param array<string, mixed> $input
     * @throws ViolationException
     */
    public function __construct(array $input)
    {
        $reflection = new \ReflectionClass(static::class);
        $type = Type::forMethodReturnType($reflection->getMethod('current'));

        $items = [];
        $violations = [];

        foreach ($input as $key => $value) {
            try {
                /** @psalm-var TValue $item */
                $item = $type->prepareValue($value);
                $items[$key] = $item;
            } catch (ViolationExceptionInterface $e) {
                $violations[] = NestedViolation::invalidProperty(
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
