<?php declare(strict_types=1);

namespace Slepic\ValueObject\Collections;

use Slepic\ValueObject\Type;
use Slepic\ValueObject\ViolationException;
use Slepic\ValueObject\ViolationExceptionInterface;

final class FromArrayConstructor
{

    private function __construct()
    {
    }

    /**
     * @psalm-template T of object
     * @psalm-param class-string<T> $class
     * @psalm-return T
     * @param string $class
     * @param array $input
     * @param bool $ignoreExtraProperties
     * @return object
     * @throws ViolationExceptionInterface
     */
    public static function constructFromArray(string $class, array $input, bool $ignoreExtraProperties = false): object
    {
        $reflection = new \ReflectionClass($class);
        $arguments = [];
        $violations = [];
        $parameters = self::getPublicConstructorParameters($reflection);
        foreach ($parameters as $parameter) {
            $key = $parameter->getName();
            $type = Type::forMethodParameterType($parameter);

            if (\array_key_exists($key, $input)) {
                $value = $input[$key];
                try {
                    $arguments[] = $type->prepareValue($value);
                } catch (ViolationExceptionInterface $e) {
                    $violations[] = CollectionViolation::invalidProperty(
                        $key,
                        $type->getExpectation(),
                        $value,
                        $e->getViolations()
                    );
                }
                unset($input[$key]);
            } elseif ($parameter->isDefaultValueAvailable()) {
                $arguments[] = $parameter->getDefaultValue();
            } else {
                $violations[] = CollectionViolation::missingRequiredProperty($key, $type->getExpectation());
            }
        }

        if (!$ignoreExtraProperties) {
            foreach ($input as $key => $value) {
                $violations[] = CollectionViolation::unknownProperty((string) $key, $value);
            }
        }

        if (\count($violations) > 0) {
            throw new ViolationException($violations);
        }

        return $reflection->newInstanceArgs($arguments);
    }

    /**
     * @psalm-template T of object
     * @psalm-param T $source
     * @psalm-return T
     * @param object $source
     * @param array $input
     * @param bool $ignoreExtraProperties
     * @return object
     * @throws ViolationExceptionInterface
     */
    public static function combineWithArray(object $source, array $input, bool $ignoreExtraProperties = false): object
    {
        /** @var class-string<T> $class */
        $class = \get_class($source);
        $reflection = new \ReflectionClass($class);
        $arguments = [];
        $violations = [];
        $parameters = self::getPublicConstructorParameters($reflection);
        foreach ($parameters as $parameter) {
            $key = $parameter->getName();
            $type = Type::forMethodParameterType($parameter);

            if (\array_key_exists($key, $input)) {
                $value = $input[$key];
                try {
                    $arguments[] = $type->prepareValue($value);
                } catch (ViolationExceptionInterface $e) {
                    $violations[] = CollectionViolation::invalidProperty(
                        $key,
                        $type->getExpectation(),
                        $value,
                        $e->getViolations()
                    );
                }
                unset($input[$key]);
            } elseif ($reflection->hasProperty($key)) {
                $property = $reflection->getProperty($key);
                if ($property->isStatic()) {
                    throw new \LogicException("Property $key cannot be static.");
                }

                $accessible = $property->isPublic();
                if (!$accessible) {
                    $property->setAccessible(true);
                }
                $value = $property->getValue($source);
                if (!$accessible) {
                    $property->setAccessible(false);
                }

                try {
                    $arguments[] = $type->prepareValue($value);
                } catch (ViolationExceptionInterface $e) {
                    throw new \LogicException(
                        "Property $key must be compatible with constructor parameter with the same name.",
                        0,
                        $e
                    );
                }
            } else {
                throw new \LogicException(
                    "Constructor parameter $key must have a corresponding non-static property."
                );
            }
        }

        if (!$ignoreExtraProperties) {
            foreach ($input as $key => $value) {
                $violations[] = CollectionViolation::unknownProperty((string) $key, $value);
            }
        }

        if (\count($violations) > 0) {
            throw new ViolationException($violations);
        }

        return $reflection->newInstanceArgs($arguments);
    }

    /**
     * @psalm-template T of object
     * @psalm-param T $source
     * @param object $source
     * @return array<string, mixed>
     */
    public static function extractConstructorArguments(object $source): array
    {
        /** @var class-string<T> $class */
        $class = \get_class($source);
        $reflection = new \ReflectionClass($class);
        $parameters = self::getPublicConstructorParameters($reflection);
        $output = [];
        foreach ($parameters as $parameter) {
            $key = $parameter->getName();
            $property = $reflection->getProperty($key);
            $accessible = $property->isPublic();
            if (!$accessible) {
                $property->setAccessible(true);
            }
            $output[$key] = $property->getValue($source);
            if (!$accessible) {
                $property->setAccessible(false);
            }
        }
        return $output;
    }

    /**
     * @param \ReflectionClass $reflection
     * @return \ReflectionParameter[]
     */
    private static function getPublicConstructorParameters(\ReflectionClass $reflection): array
    {
        $class =$reflection->getName();

        if (!$reflection->isInstantiable()) {
            throw new \LogicException("Class $class is not instantiable.");
        }

        $constructor = $reflection->getConstructor();
        if (!$constructor) {
            throw new \LogicException("Class $class does not have a constructor.");
        }

        if (!$constructor->isPublic()) {
            throw new \LogicException("Class $class does not have a public constructor.");
        }

        return $constructor->getParameters();
    }
}
