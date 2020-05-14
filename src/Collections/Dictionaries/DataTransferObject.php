<?php declare(strict_types=1);

namespace Slepic\ValueObject\Collections\Dictionaries;

use Slepic\ValueObject\Collections\CollectionException;
use Slepic\ValueObject\Collections\CollectionExceptionInterface;
use Slepic\ValueObject\InvalidTypeException;
use Slepic\ValueObject\InvalidValueException;
use Slepic\ValueObject\InvalidValueExceptionInterface;

/**
 * Represents a dictionary with a fixed set of properties and their types.
 *
 * @todo
 */
abstract class DataTransferObject
{
    /**
     * @param array $data
     * @throws CollectionExceptionInterface
     */
    public function __construct(array $data)
    {
        try {
            $reflection = new \ReflectionClass(static::class);
        } catch (\ReflectionException $e) {
            // this should never happen, but let's make IDE happy :)
            throw new \RuntimeException(
                'ReflectionClass failed for static::class',
                0,
                $e
            );
        }

        $errors = [];
        $properties = $reflection->getProperties(\ReflectionProperty::IS_PUBLIC);
        foreach ($properties as $property) {
            if ($property->isStatic()) {
                continue;
            };

            $key = $property->getName();

            try {
                if (\array_key_exists($key, $data)) {
                    $this->$key = $this->prepareProvidedProperty($property, $data[$key]);
                } else {
                    $this->checkMissingProperty($property);
                }
            } catch (InvalidValueExceptionInterface $e) {
                /*
                echo "got error for key '$key' and value:\n";
                var_dump($e->getValue());
                var_dump(\get_class($e));
                var_dump($e->getMessage());
                */
                $errors[$key] = $e;
            }
        }

        if (\count($errors) !== 0) {
            throw new CollectionException($errors, $data, 'object', 'The object has invalid or missing properties.');
        }
    }

    public function toArray(): array
    {
        return \get_object_vars($this);
    }

    private function checkMissingProperty(\ReflectionProperty $property): void
    {
        if (!$property->isInitialized($this)) {
            throw new InvalidValueException(null, 'any', 'Value is required.');
        }
    }

    /**
     * @param \ReflectionProperty $property
     * @param mixed $value
     * @return mixed
     */
    private function prepareProvidedProperty(\ReflectionProperty $property, $value)
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

        if ($value === null) {
            $this->checkNullable($targetType);
            return null;
        } elseif ($targetType->isBuiltin()) {
            /** @psalm-suppress ArgumentTypeCoercion */
            return $this->prepareBuiltin($targetType->getName(), $value);
        } else {
            /** @psalm-suppress ArgumentTypeCoercion */
            return $this->prepareObject($targetType->getName(), $value);
        }
    }

    private function checkNullable(\ReflectionNamedType $targetType): void
    {
        if (!$targetType->allowsNull()) {
            throw new InvalidValueException(null, 'not null', 'Value cannot be null');
        }
    }

    /**
     * @param string $targetTypeName
     * @param mixed $value
     * @return mixed
     */
    private function prepareBuiltin(string $targetTypeName, $value)
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
     * @return mixed
     */
    private function prepareObject(string $targetTypeName, $value)
    {
        if (\method_exists($targetTypeName, 'fromMixed')) {
            return $targetTypeName::fromMixed($value);
        }
        throw new InvalidValueException($value, 'something else');
    }
}
