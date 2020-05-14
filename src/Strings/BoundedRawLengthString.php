<?php declare(strict_types=1);

namespace Slepic\ValueObject\Strings;

abstract class BoundedRawLengthString extends StringValue
{
    abstract protected static function minLength(): int;
    abstract protected static function maxLength(): int;

    public function __construct(string $value)
    {
        $length = \strlen($value);
        $minLength = static::minLength();
        $maxLength = static::maxLength();
        if ($length < $minLength) {
            throw new StringTooLongException($minLength, $length, $value);
        }
        if ($length > $maxLength) {
            throw new StringTooLongException($maxLength, $length, $value);
        }
        parent::__construct($value);
    }
}
