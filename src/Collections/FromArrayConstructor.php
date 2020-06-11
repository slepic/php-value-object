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

        if (!$reflection->isInstantiable()) {
            throw new \InvalidArgumentException("Class $class is not instantiable.");
        }

        $constructor = $reflection->getConstructor();
        if (!$constructor) {
            throw new \InvalidArgumentException("Class $class does not have a constructor.");
        }

        if (!$constructor->isPublic()) {
            throw new \InvalidArgumentException("Class $class does not have a public constructor.");
        }

        $arguments = [];
        $violations = [];
        $parameters = $constructor->getParameters();
        foreach ($parameters as $parameter) {
            $key = $parameter->getName();
            $type = Type::forMethodParameterType($parameter);

            if (\array_key_exists($key, $input)) {
                $value = $input[$key];
                try {
                    $arguments[] = $type->prepareValue($value);
                } catch (ViolationExceptionInterface $e) {
                    $violations[] = new InvalidPropertyValue(
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
                $violations[] = new MissingRequiredProperty($key, $type->getExpectation());
            }
        }

        if (!$ignoreExtraProperties) {
            foreach ($input as $key => $value) {
                $violations[] = new UnknownProperty($key, $value);
            }
        }

        if (\count($violations) > 0) {
            throw new ViolationException($violations);
        }

        return $reflection->newInstanceArgs($arguments);
    }
}
