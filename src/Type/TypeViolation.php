<?php declare(strict_types=1);

namespace Slepic\ValueObject\Type;

use Slepic\ValueObject\Violation;
use Slepic\ValueObject\ViolationException;
use Slepic\ValueObject\ViolationExceptionInterface;

final class TypeViolation extends Violation
{
    public function __construct(string $message = '')
    {
        parent::__construct($message ?: 'Unexpected value type.');
    }

    public static function exception(string $message = ''): ViolationExceptionInterface
    {
        return ViolationException::for(new self($message));
    }
}
