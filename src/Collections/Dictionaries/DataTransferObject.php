<?php declare(strict_types=1);

namespace Slepic\ValueObject\Collections\Dictionaries;

use Slepic\ValueObject\Collections\CollectionException;
use Slepic\ValueObject\Collections\CollectionExceptionInterface;
use Slepic\ValueObject\InvalidValueException;
use Slepic\ValueObject\InvalidValueExceptionInterface;
use Slepic\ValueObject\ValueObject;

/**
 * Represents a dictionary with a fixed set of properties and their types.
 */
abstract class DataTransferObject
{
    /**
     * @param array<string, mixed> $data
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
                    $this->$key = ValueObject::prepareForProperty($property, $data[$key]);
                } else {
                    if (!$property->isInitialized($this)) {
                        throw new InvalidValueException(null, 'any', 'Value is required.');
                    }
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

        // @todo excess properties??
    }

    public function toArray(): array
    {
        return \get_object_vars($this);
    }
}
