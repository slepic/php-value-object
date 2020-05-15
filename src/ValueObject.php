<?php declare(strict_types=1);

namespace Slepic\ValueObject;

final class ValueObject
{
    /**
     * @param \ReflectionProperty $property
     * @param mixed $value
     * @return mixed
     * @throws InvalidValueExceptionInterface
     */
    public static function prepareForProperty(\ReflectionProperty $property, $value)
    {
        $key = $property->getName();

        if (!$property->hasType()) {
            throw new \RuntimeException(
                "Property $key is missing type hint."
            );
        }

        $targetType = $property->getType();
        if (!$targetType instanceof \ReflectionNamedType) {
            throw new \RuntimeException('ReflectionNamedType is not supported.');
        }

        return self::prepareForType($targetType, $value);
    }

    /**
     * @psalm-param class-string $class
     * @param string $class
     * @param string $method
     * @return callable
     */
    public static function factoryForMethodReturnType(string $class, string $method): callable
    {
        try {
            $method = new \ReflectionMethod($class, $method);
        } catch (\ReflectionException $e) {
            throw new \InvalidArgumentException($e->getMessage());
        }

        $targetType = $method->getReturnType();
        if ($targetType === null) {
            throw new \UnexpectedValueException('Method current does not have a return type.');
        }

        if (!$targetType instanceof \ReflectionNamedType) {
            throw new \RuntimeException('ReflectionNamedType not supported.');
        }

        /**
         * @psalm-suppress MissingClosureParamType
         * @psalm-suppress MissingClosureReturnType
         * @param mixed $value
         * @return mixed
         */
        return fn ($value) => self::prepareForType($targetType, $value);
    }

    /**
     * @param \ReflectionNamedType $targetType
     * @param mixed $value
     * @return mixed
     * @throws InvalidValueExceptionInterface
     */
    private static function prepareForType(\ReflectionNamedType $targetType, $value)
    {
        if ($value === null) {
            if (!$targetType->allowsNull()) {
                $expectation = new TypeExpectation($targetType->getName());
                throw new InvalidTypeException($expectation, $value);
            }
            return null;
        } elseif ($targetType->isBuiltin()) {
            /** @psalm-suppress ArgumentTypeCoercion */
            return self::prepareValueForBuiltinType($targetType->getName(), $value);
        } else {
            /** @psalm-suppress ArgumentTypeCoercion */
            return self::prepareValueForObjectType($targetType->getName(), $value);
        }
    }

    /**
     * @param string $targetType
     * @param mixed $value
     * @return array|float|int|string|bool
     * @throws InvalidTypeException
     */
    private static function prepareValueForBuiltinType(string $targetType, $value)
    {
        switch ($targetType) {
            case 'string':
                return self::toString($value);
            case 'int':
                return self::toInt($value);
            case 'float':
                return self::toFloat($value);
            case 'array':
                return self::toArray($value);
            case 'bool':
                return self::toBool($value);
            default:
                throw new \InvalidArgumentException('Only string|int|float|array are supported builtin types.');
        }
    }

    /**
     * @param mixed $value
     * @return string
     */
    private static function toString($value): string
    {
        if (\is_string($value)) {
            return $value;
        }
        if (\is_object($value) && \method_exists($value, '__toString')) {
            return (string) $value;
        }
        throw new InvalidTypeException(new TypeExpectation('string'), $value);
    }

    /**
     * @param mixed $value
     * @return int
     */
    private static function toInt($value): int
    {
        if (\is_int($value)) {
            return $value;
        }

        if (\is_object($value) && $value instanceof ToIntConvertibleInterface) {
            return $value->toInt();
        }

        throw new InvalidTypeException(new TypeExpectation('int'), $value);
    }

    /**
     * @param mixed $value
     * @return float
     */
    private static function toFloat($value): float
    {
        if (\is_float($value)) {
            return $value;
        }

        if (\is_object($value) && $value instanceof ToFloatConvertibleInterface) {
            return $value->toFloat();
        }

        throw new InvalidTypeException(new TypeExpectation('number'), $value);
    }

    /**
     * @param mixed $value
     * @return array
     */
    private static function toArray($value): array
    {
        if (\is_array($value)) {
            return $value;
        }

        if (\is_object($value) && $value instanceof ToArrayConvertibleInterface) {
            return $value->toArray();
        }

        throw new InvalidTypeException(new TypeExpectation('array'), $value);
    }

    /**
     * @param mixed $value
     * @return bool
     */
    private static function toBool($value): bool
    {
        if (\is_bool($value)) {
            return $value;
        }

        if (\is_object($value) && $value instanceof ToBoolConvertibleInterface) {
            return $value->toBool();
        }

        throw new InvalidTypeException(new TypeExpectation('bool'), $value);
    }

    /**
     * @psalm-template T
     * @psalm-param class-string<T> $class
     * @psalm-return T
     * @param string $class
     * @param mixed $value
     * @return mixed
     */
    private static function prepareValueForObjectType(string $class, $value)
    {
        try {
            $targetTypeReflection = new \ReflectionClass($class);
        } catch (\ReflectionException $e) {
            throw new \InvalidArgumentException('Non object type or unknown type passed.');
        }

        if (\is_string($value)) {
            if ($targetTypeReflection->implementsInterface(FromStringConstructableInterface::class)) {
                return $targetTypeReflection->getMethod('fromString')->invoke(null, $value);
            }
        } elseif (\is_int($value)) {
            if ($targetTypeReflection->implementsInterface(FromIntConstructableInterface::class)) {
                return $targetTypeReflection->getMethod('fromInt')->invoke(null, $value);
            }
        } elseif (\is_float($value)) {
            if ($targetTypeReflection->implementsInterface(FromFloatConstructableInterface::class)) {
                return $targetTypeReflection->getMethod('fromFloat')->invoke(null, $value);
            }
        } elseif (\is_array($value)) {
            if ($targetTypeReflection->implementsInterface(FromArrayConstructableInterface::class)) {
                return $targetTypeReflection->getMethod('fromArray')->invoke(null, $value);
            }
        } elseif (\is_bool($value)) {
            if ($targetTypeReflection->implementsInterface(FromBoolConstructableInterface::class)) {
                return $targetTypeReflection->getMethod('fromArray')->invoke(null, $value);
            }
        } elseif (\is_object($value)) {
            if ($targetTypeReflection->implementsInterface(FromObjectConstructableInterface::class)) {
                return $targetTypeReflection->getMethod('fromObject')->invoke(null, $value);
            }

            if (\is_a($value, $class)) {
                return $value;
            }
        } elseif ($targetTypeReflection->implementsInterface(FromAnyConstructableInterface::class)) {
            return $targetTypeReflection->getMethod('fromAny')->invoke(null, $value);
        }

        $expectedTypes = [];
        if ($targetTypeReflection->implementsInterface(FromStringConstructableInterface::class)) {
            $expectedTypes[] = 'string';
        }
        if ($targetTypeReflection->implementsInterface(FromIntConstructableInterface::class)) {
            $expectedTypes[] = 'int';
        }
        if ($targetTypeReflection->implementsInterface(FromFloatConstructableInterface::class)) {
            $expectedTypes[] = 'float';
        }
        if ($targetTypeReflection->implementsInterface(FromBoolConstructableInterface::class)) {
            $expectedTypes[] = 'boolean';
        }
        if ($targetTypeReflection->implementsInterface(FromArrayConstructableInterface::class)) {
            $expectedTypes[] = 'array';
        }
        if ($targetTypeReflection->implementsInterface(FromObjectConstructableInterface::class)) {
            $expectedTypes[] = 'object';
        }

        if (\count($expectedTypes) === 0) {
            throw new \Exception("Target type \"$class\" has no input type expectation.");
        }

        $expectation = new TypeExpectation(\implode('|', $expectedTypes));
        throw new InvalidTypeException($expectation, $value);
    }
}
