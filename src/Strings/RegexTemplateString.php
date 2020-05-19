<?php declare(strict_types=1);

namespace Slepic\ValueObject\Strings;

abstract class RegexTemplateString extends StringValue
{
    abstract protected static function pattern(): string;

    /**
     * @param string $value
     */
    public function __construct(string $value)
    {
        $pattern = static::pattern();
        if (1 !== \preg_match($pattern, $value)) {
            throw StringPatternViolation::exception($pattern);
        }
        parent::__construct($value);
    }
}
