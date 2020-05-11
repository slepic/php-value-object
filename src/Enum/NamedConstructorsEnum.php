<?php

namespace Slepic\ValueObject\Enum;

use Slepic\ValueObject\Strings\StringValueException;
use Slepic\ValueObject\Strings\StringValueExceptionInterface;
use Slepic\ValueObject\Strings\StringValueObject;

/**
 * This enum looks up all named constructors to build the list of allowed values.
 *
 * A named constructor is Defined as a static public function with exactly one parameter - a string parameter.
 * And the method must return instance of its own class.
 *
 * Currently there is no mechanism to ignore some methods that satisfy the criteria.
 *
 * At the same time it provides simplification to implement all those named constructors
 * using a single call to a protected method `return self::__(__METHOD__);`
 *
 * All created instances can be compared using strict comparision operator (!==,===)
 * because only one instance exists for each named constructor.
 *
 * All unique instances can be obtained using the static method `static::all()`.
 */
abstract class NamedConstructorsEnum extends StringValueObject implements StringEnumInterface
{
    /**
     * @var array<string, static>|null
     */
    private static ?array $all = null;

    private function __construct(string $value)
    {
        parent::__construct($value);
    }

    /**
     * @return array<string, static>
     */
    final public static function all(): array
    {
        if (static::$all === null) {
            static::$all = static::createAllUniqueInstances();
        }
        return static::$all;
    }

    /**
     * @param string $value
     * @return static
     * @throws StringValueExceptionInterface
     */
    final public static function fromString(string $value): self
    {
        $all = static::all();
        if (isset($all[$value])) {
            return $all[$value];
        }
        throw static::createInvalidValueException($value, $all);
    }

    /**
     * @param string $value
     * @param array<string, static> $allowed
     * @return StringValueExceptionInterface
     */
    protected static function createInvalidValueException(string $value, array $allowed): StringValueExceptionInterface
    {
        return new StringValueException(
            $value,
            \sprintf(
                'one of %s',
                \implode('|', \array_map(fn($v) => (string) $v, $allowed))
            ),
        );
    }

    /**
     * This method is intended to implement all named constructors
     *
     * Just call it in every constructor and pass __METHOD__ to it.
     *
     * This method has this weird name so that hopefully all possible collisions are avoided.
     *
     * @param string $methodName
     * @return static
     */
    final protected static function __(string $methodName): self
    {
        $parts = \explode('::', $methodName);
        if (!isset($parts[1])) {
            throw new \InvalidArgumentException(
                'Please pass __METHOD__ from a named constructor as the first argument.'
            );
        }
        try {
            return static::fromString($parts[1]);
        } catch (StringValueExceptionInterface $e) {
            throw new \LogicException(
                static::class . ' is malfunctioning or you call __() not from a named constructor.',
                (int) $e->getCode(),
                $e
            );
        }
    }

    /**
     * @return array<string, static>
     */
    private static function createAllUniqueInstances(): array
    {
        $all = [];
        $reflection = new \ReflectionClass(static::class);
        foreach ($reflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
            $instance = static::createFromReflectionMethod($method);
            if ($instance !== null) {
                $all[$method->getName()] = $instance;
            }
        }
        return $all;
    }

    /**
     * @param \ReflectionMethod $method
     * @return static|null
     */
    private static function createFromReflectionMethod(\ReflectionMethod $method): ?self
    {
        $parameters = $method->getParameters();
        if (\count($parameters) !== 0) {
            return null;
        }
        $returnType = $method->getReturnType();
        if ($returnType && $returnType instanceof \ReflectionNamedType) {
            $returnTypeName = $returnType->getName();
            if ($returnTypeName === 'self' || $returnTypeName === 'static' || $returnTypeName === static::class) {
                return new static($method->getName());
            }
        }
        return null;
    }
}
