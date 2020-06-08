<?php declare(strict_types=1);

namespace Slepic\ValueObject\Integers;

use Slepic\ValueObject\Violation;

class IntegerViolation extends Violation implements IntegerViolationInterface
{
    public function __construct(string $message = '')
    {
        parent::__construct($message ?: 'Unexpected integer value');
    }
}
