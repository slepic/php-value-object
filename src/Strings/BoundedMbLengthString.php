<?php declare(strict_types=1);

namespace Slepic\ValueObject\Strings;

abstract class BoundedMbLengthString extends MultiByteString
{
    abstract protected static function minLength(): int;
    abstract protected static function maxLength(): int;

    public function __construct(string $value)
    {
        $minLength = static::minLength();
        $maxLength = static::maxLength();
        $length = \mb_strlen($value);
        if ($length < $minLength) {
            throw new StringTooShortException($minLength, $length, $value);
        }
        if ($length > $maxLength) {
            throw new StringTooLongException($maxLength, $length, $value);
        }
        parent::__construct($value, $length);
    }
}
