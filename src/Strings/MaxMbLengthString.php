<?php declare(strict_types=1);

namespace Slepic\ValueObject\Strings;

abstract class MaxMbLengthString extends MultiByteString
{
    abstract protected static function maxLength(): int;

    public function __construct(string $value)
    {
        $maxLength = static::maxLength();
        $length = \mb_strlen($value);
        StringTooLong::check($maxLength, $length);
        parent::__construct($value, $length);
    }
}
