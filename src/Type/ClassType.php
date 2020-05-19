<?php declare(strict_types=1);

namespace Slepic\ValueObject\Type;

use Slepic\ValueObject\Type\Upcasting\FromAnyConstructableInterface;
use Slepic\ValueObject\Type\Upcasting\FromArrayConstructableInterface;
use Slepic\ValueObject\Type\Upcasting\FromBoolConstructableInterface;
use Slepic\ValueObject\Type\Upcasting\FromFloatConstructableInterface;
use Slepic\ValueObject\Type\Upcasting\FromIntConstructableInterface;
use Slepic\ValueObject\Type\Upcasting\FromObjectConstructableInterface;
use Slepic\ValueObject\Type\Upcasting\FromStringConstructableInterface;

final class ClassType implements TypeInterface
{
    private \ReflectionClass $reflection;
    private TypeExpectationInterface $expectation;

    public function __construct(\ReflectionClass $reflection)
    {
        $this->reflection = $reflection;
        $this->expectation = new TypeExpectation(
            $this->reflection->getName(),
            $this->reflection->implementsInterface(FromStringConstructableInterface::class),
            $this->reflection->implementsInterface(FromIntConstructableInterface::class),
            $this->reflection->implementsInterface(FromFloatConstructableInterface::class),
            $this->reflection->implementsInterface(FromBoolConstructableInterface::class),
            $this->reflection->implementsInterface(FromArrayConstructableInterface::class),
        );
    }

    public function getExpectation(): TypeExpectationInterface
    {
        return $this->expectation;
    }

    public function prepareValue($value)
    {
        $expectation = $this->getExpectation();

        if (\is_string($value)) {
            if ($expectation->acceptsString()) {
                return $this->reflection->getMethod('fromString')->invoke(null, $value);
            }
        } elseif (\is_int($value)) {
            if ($expectation->acceptsInt()) {
                return $this->reflection->getMethod('fromInt')->invoke(null, $value);
            }
        } elseif (\is_float($value)) {
            if ($expectation->acceptsFloat()) {
                return $this->reflection->getMethod('fromFloat')->invoke(null, $value);
            }
        } elseif (\is_array($value)) {
            if ($expectation->acceptsArray()) {
                return $this->reflection->getMethod('fromArray')->invoke(null, $value);
            }
        } elseif (\is_bool($value)) {
            if ($expectation->acceptsBool()) {
                return $this->reflection->getMethod('fromBool')->invoke(null, $value);
            }
        } elseif (\is_object($value)) {
            if ($this->reflection->implementsInterface(FromObjectConstructableInterface::class)) {
                return $this->reflection->getMethod('fromObject')->invoke(null, $value);
            }

            if (\is_a($value, $this->reflection->getName())) {
                return $value;
            }
        } elseif ($this->reflection->implementsInterface(FromAnyConstructableInterface::class)) {
            return $this->reflection->getMethod('fromAny')->invoke(null, $value);
        }

        throw TypeViolation::exception();
    }
}
