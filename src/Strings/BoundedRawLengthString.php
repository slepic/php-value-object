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
        StringLengthOutOfBounds::check($minLength, $maxLength, $length);
        parent::__construct($value);
    }
}
