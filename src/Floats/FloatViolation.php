<?php declare(strict_types=1);

namespace Slepic\ValueObject\Floats;

use Slepic\ValueObject\Violation;

class FloatViolation extends Violation implements FloatViolationInterface
{
    public function __construct(string $message = '')
    {
        parent::__construct($message ?: 'Invalid float value.');
    }
}
