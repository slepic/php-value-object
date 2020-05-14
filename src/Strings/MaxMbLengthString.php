<?php declare(strict_types=1);

namespace Slepic\ValueObject\Strings;

abstract class MaxMbLengthString extends StringValue
{
    abstract protected static function maxLength(): int;

    public function __construct(string $value)
    {
        $maxLength = static::maxLength();
        $length = \mb_strlen($value);
        if ($length > $maxLength) {
            throw new StringTooLongException($maxLength, $length, $value);
        }
        parent::__construct($value);
    }
}
