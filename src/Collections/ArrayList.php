<?php declare(strict_types=1);

namespace Slepic\ValueObject\Collections;

use Slepic\ValueObject\Type;
use Slepic\ValueObject\Type\TypeViolation;
use Slepic\ValueObject\ViolationException;
use Slepic\ValueObject\ViolationExceptionInterface;

/**
 * Represents an array value object with keys being consecutive integers starting at 0.
 *
 * All values must have the same (super) type.
 *
 * @template TValue
 * @template-extends ImmutableArrayIterator<int, TValue>
 */
abstract class ArrayList extends ImmutableArrayIterator implements \JsonSerializable
{
    /**
     * @psalm-param array<int, TValue> $input
     * @param array<int, mixed> $input
     * @throws ViolationExceptionInterface
     */
    public function __construct(array $input)
    {
        $reflection = new \ReflectionClass($this);
        $type = Type::forMethodReturnType($reflection->getMethod('current'));

        $index = 0;
        $items = [];
        $violations = [];
        foreach ($input as $key => $value) {
            if ($key !== $index) {
                throw TypeViolation::exception('Expected 0-based indices.');
            }

            try {
                /** @psalm-var TValue $item */
                $item = $type->prepareValue($value);
                $items[] = $item;
            } catch (ViolationExceptionInterface $e) {
                $violations[] = NestedViolation::invalidItem(
                    $key,
                    $type->getExpectation(),
                    $value,
                    $e->getViolations()
                );
            }

            ++$index;
        }

        if (\count($violations) > 0) {
            throw new ViolationException($violations);
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
