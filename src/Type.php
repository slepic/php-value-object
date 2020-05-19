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

final class Type
{
    public const PHP_STRING = 'string';
    public const PHP_INT = 'int';
    public const PHP_FLOAT = 'float';
    public const PHP_BOOL = 'bool';
    public const PHP_ARRAY = 'array';

    private function __construct()
    {
    }

    private static function forTypeReflection(\ReflectionNamedType $reflectionType): TypeInterface
    {
        if ($reflectionType->isBuiltin()) {
            $type = self::forBuiltinType($reflectionType->getName());
        } else {
            /** @psalm-suppress ArgumentTypeCoercion */
            $reflection = new \ReflectionClass($reflectionType->getName());
            $type = self::forClassReflection($reflection);
        }

        if ($reflectionType->allowsNull()) {
            return new NullableType($type);
        }

        return $type;
    }

    private static function forBuiltinType(string $name): TypeInterface
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
            throw new \UnexpectedValueException('Method current does not have a return type.');
        }

        if (!$type instanceof \ReflectionNamedType) {
            throw new \RuntimeException('ReflectionNamedType not supported.');
        }

        return self::forTypeReflection($type);
    }
}
