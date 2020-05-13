<?php declare(strict_types=1);

namespace Slepic\ValueObject\Enum;

use Slepic\ValueObject\Strings\StringValue;

abstract class StringEnumBase extends StringValue
{
    /**
     * @var array<string, array<string, static>>|null
     */
    private static array $all = [];

    private function __construct(string $value)
    {
        parent::__construct($value);
    }

    /**
     * @return array<string, static>
     */
    final public static function all(): array
    {
        if (!isset(static::$all[static::class])) {
            $all = [];
            foreach (static::createAllUniqueValues() as $value) {
                $all[$value] = new static($value);
            }
            static::$all[static::class] = $all;
        }
        return static::$all[static::class];
    }

    /**
     * @param string $value
     * @return static
     * @throws StringEnumExceptionInterface
     */
    final public static function fromString(string $value): self
    {
        $all = static::all();
        if (isset($all[$value])) {
            return $all[$value];
        }
        throw static::createInvalidValueException($value, $all);
    }

    /**
     * @param string $value
     * @param array<string, static> $allowed
     * @return StringEnumExceptionInterface
     */
    protected static function createInvalidValueException(string $value, array $allowed): StringEnumExceptionInterface
    {
        return new StringEnumException(
            \array_keys($allowed),
            $value,
            \sprintf(
                'one of %s',
                \implode('|', \array_map(fn($v) => (string) $v, $allowed))
            ),
        );
    }

    /**
     * @return array<string>
     */
    abstract protected static function createAllUniqueValues(): array;
}
