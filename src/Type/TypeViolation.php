<?php declare(strict_types=1);

namespace Slepic\ValueObject\Type;

use Slepic\ValueObject\ViolationException;
use Slepic\ValueObject\ViolationExceptionInterface;
use Slepic\ValueObject\ViolationInterface;

final class TypeViolation implements ViolationInterface
{
    public static function exception(): ViolationExceptionInterface
    {
        return new ViolationException([new self()]);
    }
}
