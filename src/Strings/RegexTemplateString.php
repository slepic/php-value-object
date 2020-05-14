<?php declare(strict_types=1);

namespace Slepic\ValueObject\Strings;

abstract class RegexTemplateString extends StringValue
{
    abstract protected static function pattern(): string;

    public function __construct(string $value)
    {
        $pattern = static::pattern();
        if (1 !== \preg_match($pattern, $value)) {
            throw new StringException($value, $pattern);
        }
        parent::__construct($value);
    }
}
