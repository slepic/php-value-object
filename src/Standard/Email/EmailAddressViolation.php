<?php declare(strict_types=1);

namespace Slepic\ValueObject\Standard\Email;

use Slepic\ValueObject\Strings\StringViolation;
use Slepic\ValueObject\ViolationException;
use Slepic\ValueObject\ViolationExceptionInterface;

final class EmailAddressViolation extends StringViolation
{
    public function __construct(string $message = '')
    {
        parent::__construct($message ?: 'Invalid email address.');
    }

    public static function exception(): ViolationExceptionInterface
    {
        return new ViolationException([new self()]);
    }
}
