<?php declare(strict_types=1);

namespace Slepic\ValueObject\Strings;

abstract class MinRawLengthString extends StringValue
{
    abstract protected static function minLength(): int;

    public function __construct(string $value)
    {
        $length = \strlen($value);
        $minLength = static::minLength();
        StringTooShort::check($minLength, $length);
        parent::__construct($value);
    }
}
