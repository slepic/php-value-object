<?php declare(strict_types=1);

namespace Slepic\ValueObject\Enum;

use Slepic\ValueObject\Strings\StringValue;
use Slepic\ValueObject\Type\Upcasting\FromStringConstructableInterface;

abstract class StringEnumBase extends StringValue implements FromStringConstructableInterface
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
     */
    final public static function fromString(string $value): self
    {
        $all = static::all();
        if (isset($all[$value])) {
            return $all[$value];
        }
        throw StringEnumViolation::exception(\array_keys($all));
    }

    /**
     * @return array<string>
     */
    abstract protected static function createAllUniqueValues(): array;

    /**
     * @throws \BadMethodCallException
     */
    public function __clone()
    {
        throw new \BadMethodCallException('StrongEnum cannot be cloned.');
    }
}
