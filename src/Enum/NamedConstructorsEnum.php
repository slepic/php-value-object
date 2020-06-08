<?php declare(strict_types=1);

namespace Slepic\ValueObject\Enum;

use Slepic\ValueObject\ViolationExceptionInterface;

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
abstract class NamedConstructorsEnum extends StringEnumBase
{

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
        } catch (ViolationExceptionInterface $e) {
            throw new \LogicException(
                static::class . ' is malfunctioning or you call __() not from a named constructor.',
                (int) $e->getCode(),
                $e
            );
        }
    }

    final protected static function createAllUniqueValues(): array
    {
        $all = [];
        $reflection = new \ReflectionClass(static::class);
        $methods = $reflection->getMethods(\ReflectionMethod::IS_PUBLIC | \ReflectionMethod::IS_STATIC);
        foreach ($methods as $method) {
            if (static::isUsableNamedConstructor($method)) {
                $methodName = $method->getName();
                $all[] = $methodName;
            }
        }
        return $all;
    }

    /**
     * @param \ReflectionMethod $method
     * @return bool
     */
    private static function isUsableNamedConstructor(\ReflectionMethod $method): bool
    {
        $parameters = $method->getParameters();
        if (\count($parameters) !== 0) {
            return false;
        }
        $returnType = $method->getReturnType();
        if ($returnType && $returnType instanceof \ReflectionNamedType) {
            $returnTypeName = $returnType->getName();
            if ($returnTypeName === 'self' || $returnTypeName === 'static' || $returnTypeName === static::class) {
                return true;
            }
        }
        return false;
    }
}
