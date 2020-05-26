<?php declare(strict_types=1);

namespace Slepic\ValueObject\Collections;

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
     * @throws ViolationExceptionInterface
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
                    $violations[] = new InvalidPropertyValue(
                        $key,
                        $type->getExpectation(),
                        $value,
                        $e->getViolations()
                    );
                }
            } else {
                if (!$property->isInitialized($this)) {
                    $violations[] = new MissingRequiredProperty($key, $type->getExpectation());
                }
            }
        }

        if (\count($violations) !== 0) {
            throw new ViolationException($violations);
        }
    }

    public function toArray(): array
    {
        return \get_object_vars($this);
    }
}
