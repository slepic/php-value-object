<?php declare(strict_types=1);

namespace Slepic\ValueObject\Strings;

use Slepic\ValueObject\ViolationExceptionInterface;

abstract class RegexTemplateString extends StringValue
{
    abstract protected static function pattern(): string;

    /**
     * @param string $value
     * @throws ViolationExceptionInterface
     */
    public function __construct(string $value)
    {
        $pattern = static::pattern();
        StringPatternViolation::check($pattern, $value);
        parent::__construct($value);
    }
}
