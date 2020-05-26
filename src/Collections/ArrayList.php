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
 */
abstract class ArrayList extends ImmutableArrayIterator implements \JsonSerializable
{
    /**
     * @param array $value
     * @throws ViolationExceptionInterface
     */
    public function __construct(array $value)
    {
        $reflection = new \ReflectionClass($this);
        $type = Type::forMethodReturnType($reflection->getMethod('current'));

        $index = 0;
        $items = [];
        $violations = [];
        foreach ($value as $key => $item) {
            if ($key !== $index) {
                throw TypeViolation::exception('Expected 0-based indices.');
            }

            try {
                $items[] = $type->prepareValue($item);
            } catch (ViolationExceptionInterface $e) {
                $violations[] = new InvalidListItem(
                    $key,
                    $type->getExpectation(),
                    $item,
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
