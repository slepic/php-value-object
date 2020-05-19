<?php declare(strict_types=1);

namespace Slepic\ValueObject\Strings;

use Slepic\ValueObject\ViolationException;
use Slepic\ValueObject\ViolationExceptionInterface;

final class StringTooLong extends StringLengthViolation
{
    private int $maxLength;

    public function __construct(int $maxLength, int $length)
    {
        $this->maxLength = $maxLength;
        parent::__construct($length);
    }

    public function getMaxLength(): int
    {
        return $this->maxLength;
    }

    public static function exception(int $maxLength, int $length): ViolationExceptionInterface
    {
        return new ViolationException([new self($maxLength, $length)]);
    }

    public static function check(int $maxLength, int $length): void
    {
        if ($length > $maxLength) {
            throw self::exception($maxLength, $length);
        }
    }
}
