<?php declare(strict_types=1);

namespace Slepic\ValueObject\Collections;

use Slepic\ValueObject\Type\Downcasting\ToArrayConvertibleInterface;
use Slepic\ValueObject\Type\Upcasting\FromArrayConstructableInterface;

/**
 * @template-extends ArrayList<int>
 */
final class ListOfInts extends ArrayList implements
    ToArrayConvertibleInterface,
    FromArrayConstructableInterface
{
    public function current(): int
    {
        return parent::current();
    }

    public static function fromArray(array $value): self
    {
        return new self($value);
    }
}
