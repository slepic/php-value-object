<?php declare(strict_types=1);

namespace Slepic\ValueObject\Strings;

abstract class MaxRawLengthString extends StringValue
{
    abstract protected static function maxLength(): int;

    public function __construct(string $value)
    {
        $length = \strlen($value);
        $maxLength = static::maxLength();
        StringTooLong::check($maxLength, $length);
        parent::__construct($value);
    }
}
