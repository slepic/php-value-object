<?php declare(strict_types=1);

namespace Slepic\ValueObject\Strings;

abstract class MinMbLengthString extends MultiByteString
{
    abstract protected static function minLength(): int;

    public function __construct(string $value)
    {
        $minLength = static::minLength();
        $length = \mb_strlen($value);
        StringTooShort::check($minLength, $length);
        parent::__construct($value, $length);
    }
}
