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
    protected const IGNORE_UNKNOWN_PROPERTIES = false;

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

            unset($data[$key]);
        }

        if (!static::IGNORE_UNKNOWN_PROPERTIES && \count($data) > 0) {
            foreach ($data as $key => $value) {
                $violations[] = new UnknownProperty($key, $value);
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
