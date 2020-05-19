<?php declare(strict_types=1);

namespace Slepic\ValueObject\Strings;

use Slepic\ValueObject\ViolationException;
use Slepic\ValueObject\ViolationExceptionInterface;
use Slepic\ValueObject\ViolationInterface;

final class StringPatternViolation implements ViolationInterface
{
    private string $pattern;

    public function __construct(string $pattern)
    {
        $this->pattern = $pattern;
    }

    public function getPattern(): string
    {
        return $this->pattern;
    }

    public static function exception(string $pattern): ViolationExceptionInterface
    {
        return new ViolationException([new self($pattern)]);
    }
}
