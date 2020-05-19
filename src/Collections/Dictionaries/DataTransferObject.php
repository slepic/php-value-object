<?php declare(strict_types=1);

namespace Slepic\ValueObject\Collections\Dictionaries;

use Slepic\ValueObject\Collections\CollectionViolation;
use Slepic\ValueObject\Collections\MissingRequiredProperty;
use Slepic\ValueObject\Error;
use Slepic\ValueObject\Type;
use Slepic\ValueObject\ViolationException;
use Slepic\ValueObject\ViolationExceptionInterface;

/**
 * Represents a dictionary with a fixed set of properties and their types.
 */
abstract class DataTransferObject
{
    /**
     * @param array<string, mixed> $data
     */
    public function __construct(array $data)
    {
        $reflection = new \ReflectionClass(static::class);

        $violations = [];
        $properties = $reflection->getProperties(\ReflectionProperty::IS_PUBLIC);
        foreach ($properties as $property) {
            if ($property->isStatic()) {
                continue;
            };

            $key = $property->getName();
            $type = Type::forPropertyReflection($property);

            if (\array_key_exists($key, $data)) {
                $value = $data[$key];
                try {
                    $this->$key = $type->prepareValue($value);
                } catch (ViolationExceptionInterface $e) {
                    $error = new Error($type->getExpectation(), $value, ...$e->getViolations());
                    $violations[] = new CollectionViolation($key, $error);
                }
            } else {
                if (!$property->isInitialized($this)) {
                    $error = new Error($type->getExpectation(), null, new MissingRequiredProperty());
                    $violations[] = new CollectionViolation($key, $error);
                }
            }
        }



        if (\count($violations) !== 0) {
            throw new ViolationException($violations);
        }

        // @todo excess properties??
    }

    public function toArray(): array
    {
        return \get_object_vars($this);
    }
}
