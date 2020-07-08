<?php declare(strict_types=1);

namespace Slepic\ValueObject\Enum;

use Slepic\ValueObject\Strings\StringValue;
use Slepic\ValueObject\Type\Upcasting\FromStringConstructableInterface;

class ConstantStringValuesWeakEnum extends StringValue implements FromStringConstructableInterface
{
    private function __construct(string $value)
    {
        parent::__construct($value);
    }

    final public static function all(): array
    {
        $all = [];
        foreach (static::allowedValues() as $value) {
            $all[$value] = new static($value);
        }
        return $all;
    }

    final public static function fromString(string $value): self
    {
        return new static($value);
    }

    /**
     * @return array<string>
     */
    public static function allowedValues(): array
    {
        $reflection = new \ReflectionClass(static::class);
        return ClassConstantsReflection::getUniqueStringConstantValues($reflection);
    }

    /**
     * @param mixed $other
     * @return bool
     */
    public function is($other): bool
    {
        if (\is_string($other)) {
            return ((string) $this) === $other;
        }

        if (\is_object($other) && \is_a($other, static::class)) {
            return ((string) $this) === ((string) $other);
        }

        throw new \InvalidArgumentException('Expected string or instance of ' . static::class);
    }
}
