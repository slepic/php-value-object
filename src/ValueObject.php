<?php declare(strict_types=1);

namespace Slepic\ValueObject;

final class ValueObject
{
    /**
     * @param \ReflectionNamedType $targetType
     * @param mixed $value
     * @return mixed
     * @throws InvalidValueExceptionInterface
     */
    public static function prepare(\ReflectionNamedType $targetType, $value)
    {
        if ($value === null) {
            self::checkNullable($targetType);
            return null;
        } elseif ($targetType->isBuiltin()) {
            /** @psalm-suppress ArgumentTypeCoercion */
            return self::prepareBuiltin($targetType->getName(), $value);
        } else {
            /** @psalm-suppress ArgumentTypeCoercion */
            return self::prepareObject($targetType->getName(), $value);
        }
    }

    /**
     * @param \ReflectionNamedType $targetType
     * @throws InvalidValueExceptionInterface
     */
    private static function checkNullable(\ReflectionNamedType $targetType): void
    {
        if (!$targetType->allowsNull()) {
            throw new InvalidValueException(null, 'not null', 'Value cannot be null');
        }
    }

    /**
     * @param string $targetTypeName
     * @param mixed $value
     * @return mixed
     * @throws InvalidValueExceptionInterface
     */
    private static function prepareBuiltin(string $targetTypeName, $value)
    {
        if ($targetTypeName === 'int') {
            $targetTypeName = 'integer';
        } elseif ($targetTypeName === 'float') {
            $targetTypeName = 'double';
        }
        if ($targetTypeName !== \gettype($value)) {
            throw new InvalidTypeException($value, $targetTypeName);
        }
        return $value;
    }

    /**
     * @psalm-template T
     * @psalm-param class-string<T> $targetTypeName
     * @psalm-return T
     * @param string $targetTypeName
     * @param mixed $value
     * @return object
     * @throws InvalidValueExceptionInterface
     *
     * @todo check named constructors
     */
    private static function prepareObject(string $targetTypeName, $value)
    {
        try {
            $targetTypeReflection = new \ReflectionClass($targetTypeName);
        } catch (\ReflectionException $e) {
            throw new \InvalidArgumentException('Non object type or unknown type passed.');
        }
        if (!$targetTypeReflection->isInstantiable()) {
            throw new \InvalidArgumentException('Class is not instantiable.');
        }

        $constructor = $targetTypeReflection->getConstructor();
        if ($constructor === null) {
            throw new \InvalidArgumentException('There is no constructor');
        }

        if (!$constructor->isPublic()) {
            throw new \InvalidArgumentException('Cannot use constructor, not public');
        }

        if ($constructor->getNumberOfRequiredParameters() > 1) {
            throw new \InvalidArgumentException('Class must have at most 1 required constructor parameter.');
        }

        if ($constructor->getNumberOfParameters() === 0) {
            throw new \InvalidArgumentException('Class must have at least one constructor parameter.');
        }

        try {
            return $targetTypeReflection->newInstance($value);
        } catch (\TypeError $e) {
            $expectedType = TypeError::getExpectedType($e);
            throw new InvalidTypeException($value, $expectedType);
        }
    }


    /**
     * @psalm-param class-string $class
     * @param string $class
     * @param string $method
     * @return callable
     */
    public static function forMethodReturnType(string $class, string $method): callable
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
        return fn ($value) => self::prepare($targetType, $value);
    }
}
