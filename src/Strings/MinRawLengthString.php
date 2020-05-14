<?php declare(strict_types=1);

namespace Slepic\ValueObject\Strings;

abstract class MinRawLengthString extends StringValue
{
    abstract protected static function minLength(): int;

    public function __construct(string $value)
    {
        $length = \strlen($value);
        $minLength = static::minLength();
        if ($length < $minLength) {
            throw new StringTooLongException($minLength, $length, $value);
        }
        parent::__construct($value);
    }
}
