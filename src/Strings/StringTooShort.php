<?php declare(strict_types=1);

namespace Slepic\ValueObject\Strings;

use Slepic\ValueObject\ViolationException;
use Slepic\ValueObject\ViolationExceptionInterface;

final class StringTooShort extends StringLengthViolation
{
    private int $minLength;

    public function __construct(int $minLength, int $length)
    {
        $this->minLength = $minLength;
        parent::__construct($length);
    }

    public function getMinLength(): int
    {
        return $this->minLength;
    }

    public static function exception(int $minLength, int $length): ViolationExceptionInterface
    {
        return new ViolationException([new self($minLength, $length)]);
    }

    public static function check(int $minLength, int $length): void
    {
        if ($length < $minLength) {
            throw self::exception($minLength, $length);
        }
    }
}
