<?php declare(strict_types=1);

namespace Slepic\ValueObject\Enum;

abstract class ConstantStringValuesEnum extends StringEnumBase
{
    protected static function createAllUniqueValues(): array
    {
        $all = [];
        $reflection = new \ReflectionClass(static::class);
        $constants = $reflection->getReflectionConstants();
        foreach ($constants as $constant) {
            if ($constant->isPublic()) {
                $name = $constant->getName();
                $value = $constant->getValue();
                if (!\is_string($value)) {
                    throw new \UnexpectedValueException("Constant \"$name\" does not have a string value.");
                }
                if (isset($all[$value])) {
                    throw new \UnexpectedValueException("Constant \"$name\" has duplicate value.");
                }
                $all[$value] = $value;
            }
        }
        return $all;
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return static
     * @throws StringEnumExceptionInterface
     */
    public static function __callStatic(string $name, array $arguments): self
    {
        if (\count($arguments) !== 0) {
            throw new \BadMethodCallException("Method $name does not exist.");
        }
        return static::fromString($name);
    }
}
