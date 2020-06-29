<?php declare(strict_types=1);

namespace Slepic\ValueObject;

use Slepic\ValueObject\Type\ArrayType;
use Slepic\ValueObject\Type\BoolType;
use Slepic\ValueObject\Type\ClassType;
use Slepic\ValueObject\Type\FloatType;
use Slepic\ValueObject\Type\IntType;
use Slepic\ValueObject\Type\NullableType;
use Slepic\ValueObject\Type\StringType;
use Slepic\ValueObject\Type\TypeInterface;
use Slepic\ValueObject\Type\VoidType;

final class Type
{
    private const PHP_STRING = 'string';
    private const PHP_INT = 'int';
    private const PHP_FLOAT = 'float';
    private const PHP_BOOL = 'bool';
    private const PHP_ARRAY = 'array';
    private const PHP_VOID = 'void';

    private function __construct()
    {
    }

    private static function forTypeReflection(\ReflectionNamedType $reflectionType): TypeInterface
    {
        if ($reflectionType->isBuiltin()) {
            $type = self::forBuiltinType($reflectionType->getName());
        } else {
            /** @psalm-suppress ArgumentTypeCoercion */
            $type = self::forClass($reflectionType->getName());
        }

        if ($reflectionType->allowsNull()) {
            return new NullableType($type);
        }

        return $type;
    }

    /**
     * @psalm-param class-string $class
     * @param string $class
     * @return TypeInterface
     */
    public static function forClass(string $class): TypeInterface
    {
        $reflection = new \ReflectionClass($class);
        return self::forClassReflection($reflection);
    }

    public static function forBuiltinType(string $name): TypeInterface
    {
        switch ($name) {
            case self::PHP_STRING:
                return new StringType();
            case self::PHP_INT:
                return new IntType();
            case self::PHP_FLOAT:
                return new FloatType();
            case self::PHP_BOOL:
                return new BoolType();
            case self::PHP_ARRAY:
                return new ArrayType();
            case self::PHP_VOID:
                return new VoidType();
            default:
                throw new \InvalidArgumentException("Not a builtin type: $name.");
        }
    }

    private static function forClassReflection(\ReflectionClass $class): TypeInterface
    {
        return new ClassType($class);
    }

    public static function forPropertyReflection(\ReflectionProperty $property): TypeInterface
    {
        $key = $property->getName();

        if (!$property->hasType()) {
            throw new \RuntimeException(
                "Property $key is missing type hint."
            );
        }

        $type = $property->getType();
        if (!$type instanceof \ReflectionNamedType) {
            throw new \RuntimeException('ReflectionNamedType is not supported.');
        }

        return self::forTypeReflection($type);
    }

    public static function forMethodReturnType(\ReflectionMethod $method): TypeInterface
    {
        $type = $method->getReturnType();
        if ($type === null) {
            throw new \UnexpectedValueException(
                'Method ' . $method->getName() . ' does not have a return type.'
            );
        }

        if (!$type instanceof \ReflectionNamedType) {
            throw new \RuntimeException('ReflectionNamedType not supported.');
        }

        return self::forTypeReflection($type);
    }


    public static function forMethodParameterType(\ReflectionParameter $parameter): TypeInterface
    {
        $type = $parameter->getType();
        if ($type === null) {
            throw new \UnexpectedValueException('Method current does not have a return type.');
        }

        if (!$type instanceof \ReflectionNamedType) {
            throw new \RuntimeException('ReflectionNamedType not supported.');
        }

        return self::forTypeReflection($type);
    }
}
