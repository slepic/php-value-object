<?php declare(strict_types=1);

namespace Slepic\ValueObject\Standard\Email;

use Slepic\ValueObject\ViolationException;
use Slepic\ValueObject\ViolationExceptionInterface;
use Slepic\ValueObject\ViolationInterface;

final class EmailAddressViolation implements ViolationInterface
{
    public static function exception(): ViolationExceptionInterface
    {
        return new ViolationException([new self()]);
    }
}
