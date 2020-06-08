<?php declare(strict_types=1);

namespace Slepic\ValueObject\DateTime;

use Slepic\ValueObject\Strings\StringViolation;
use Slepic\ValueObject\ViolationException;
use Slepic\ValueObject\ViolationExceptionInterface;

final class DateTimeFormatViolation extends StringViolation
{
    private string $format;

    public function __construct(string $format, string $message = '')
    {
        $this->format = $format;
        parent::__construct($message ?: "Expected date in format \"$format\".");
    }

    public function getFormat(): string
    {
        return $this->format;
    }

    public static function exception(string $format): ViolationExceptionInterface
    {
        return ViolationException::for(new self($format));
    }
}
