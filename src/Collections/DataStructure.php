<?php declare(strict_types=1);

namespace Slepic\ValueObject\Collections;

use Slepic\ValueObject\ImmutableObjectTrait;
use Slepic\ValueObject\Type\Downcasting\ToArrayConvertibleInterface;
use Slepic\ValueObject\Type\Upcasting\FromArrayConstructableInterface;

abstract class DataStructure implements FromArrayConstructableInterface, ToArrayConvertibleInterface
{
    use ImmutableObjectTrait;

    /**
     * @param array $value
     * @return static
     * @throws \Slepic\ValueObject\ViolationExceptionInterface
     */
    public static function fromArray(array $value): self
    {
        return FromArrayConstructor::constructFromArray(static::class, $value);
    }

    /**
     * @param array $modifiedProperties
     * @return static
     * @throws \Slepic\ValueObject\ViolationExceptionInterface
     */
    public function with(array $modifiedProperties): self
    {
        return FromArrayConstructor::combineWithArray($this, $modifiedProperties);
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return FromArrayConstructor::extractConstructorArguments($this);
    }
}
