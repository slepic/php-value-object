<?php declare(strict_types=1);

namespace Slepic\ValueObject\Strings;

use Slepic\ValueObject\ViolationException;
use Slepic\ValueObject\ViolationExceptionInterface;

final class StringLengthOutOfBounds extends StringLengthViolation
{
    private int $minLength;
    private int $maxLength;

    public function __construct(int $minLength, int $maxLength, int $length, string $message = '')
    {
        $this->minLength = $minLength;
        $this->maxLength = $maxLength;
        parent::__construct(
            $length,
            $message ?: "Value of length $length is out of boundaries [$minLength, $maxLength]."
        );
    }

    public function getMinLength(): int
    {
        return $this->minLength;
    }

    public function getMaxLength(): int
    {
        return $this->maxLength;
    }

    public static function exception(int $minLength, int $maxLength, int $actualLength): ViolationExceptionInterface
    {
        return ViolationException::for(new self($minLength, $maxLength, $actualLength));
    }

    public static function check(int $minLength, int $maxLength, int $actualLength): void
    {
        if ($actualLength < $minLength || $actualLength > $maxLength) {
            throw self::exception($minLength, $maxLength, $actualLength);
        }
    }
}
