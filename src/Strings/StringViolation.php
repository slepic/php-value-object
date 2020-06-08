<?php declare(strict_types=1);

namespace Slepic\ValueObject\Strings;

use Slepic\ValueObject\Violation;

class StringViolation extends Violation implements StringViolationInterface
{
    public function __construct(string $message = '')
    {
        parent::__construct($message ?: 'Invalid string value.');
    }
}
