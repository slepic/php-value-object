<?php declare(strict_types=1);

namespace Slepic\ValueObject\Collections;

use Slepic\ValueObject\Type\Downcasting\ToArrayConvertibleInterface;
use Slepic\ValueObject\Type\Upcasting\FromArrayConstructableInterface;

/**
 * @template-extends ArrayList<string>
 */
final class ListOfStrings extends ArrayList implements
    ToArrayConvertibleInterface,
    FromArrayConstructableInterface
{
    public function current(): string
    {
        return parent::current();
    }

    /**
     * @param array $value
     * @return static
     * @throws \Slepic\ValueObject\ViolationExceptionInterface
     */
    public static function fromArray(array $value): self
    {
        return new self($value);
    }
}
