<?php declare(strict_types=1);

namespace Slepic\ValueObject\Enum;

abstract class ConstantStringValuesEnum extends StringEnumBase
{
    protected static function createAllUniqueValues(): array
    {
        $reflection = new \ReflectionClass(static::class);
        return ClassConstantsReflection::getUniqueStringConstantValues($reflection);
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return static
     */
    public static function __callStatic(string $name, array $arguments): self
    {
        if (\count($arguments) !== 0) {
            throw new \BadMethodCallException("Method $name does not exist.");
        }
        return static::fromString($name);
    }
}
