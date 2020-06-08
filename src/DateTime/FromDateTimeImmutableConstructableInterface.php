<?php declare(strict_types=1);

namespace Slepic\ValueObject\DateTime;

use Slepic\ValueObject\Type\Upcasting\FromObjectConstructableInterface;

/**
 * Refines FromObjectConstructableInterface's capability
 * and further specifies the type of underlying object to at least \DateTimeImmutable instances.
 */
interface FromDateTimeImmutableConstructableInterface extends FromObjectConstructableInterface
{
    /**
     * Try to create instance from an object of unspecified type.
     *
     * Whenever $value is instance of \DateTimeImmutable, the method
     * must have the same behaviour as calling `static::fromDateTimeImmutable($value)`.
     *
     * @psalm-suppress LessSpecificImplementedReturnType
     * @param object|\DateTimeImmutable $value
     * @return static
     */
    public static function fromObject(object $value): self;

    /**
     * @param \DateTimeImmutable $value
     * @return static
     */
    public static function fromDateTimeImmutable(\DateTimeImmutable $value): self;
}
