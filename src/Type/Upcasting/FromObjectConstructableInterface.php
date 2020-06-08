<?php declare(strict_types=1);

namespace Slepic\ValueObject\Type\Upcasting;

/**
 * Allows composite value objects to automatically create their components from their underlying object data types.
 */
interface FromObjectConstructableInterface
{
    /**
     * Try to creates a new instance of the the implementing class from given object
     *
     * A ViolationExceptionInterface must be thrown when given object does not have a supported type.
     *
     * @param object $value
     * @return static
     */
    public static function fromObject(object $value): self;
}
